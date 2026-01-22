import pandas as pd
import os
import json
from pathlib import Path

# List of estimate files to analyze
estimate_files = [
    r"E:\DSR\docs\Estimate NGO -sd no 2 -parli.xls",
    r"E:\DSR\docs\M.I.Section No 14 . Sirsala Dy-15 to 19.xls",
    r"E:\DSR\docs\Majalgaon Dam Repairs of Old L drain pitching 690 to 1140.xls",
    r"E:\DSR\docs\Renovetion of office Building SD No.2 Gangakhed.xls",
    r"E:\DSR\docs\Repairing of W.C.& Toilet Of M.I.DIvision Office Parli (v)New 9-9-2025.xls",
    r"E:\DSR\docs\Repairs of Office  Building & Other Minor work of Sub Division No. 9  SirsalaCorrection file.xls",
]

analysis_results = {}

for file_path in estimate_files:
    file_name = os.path.basename(file_path)
    print(f"\n{'='*80}")
    print(f"Analyzing: {file_name}")
    print(f"{'='*80}\n")
    
    try:
        # Read Excel file - get all sheet names first
        xls = pd.ExcelFile(file_path)
        
        file_analysis = {
            'file_name': file_name,
            'file_size': os.path.getsize(file_path),
            'sheets': []
        }
        
        print(f"Number of sheets: {len(xls.sheet_names)}")
        print(f"Sheet names: {xls.sheet_names}\n")
        
        # Analyze each sheet
        for sheet_name in xls.sheet_names:
            print(f"\n--- Sheet: {sheet_name} ---")
            
            # Read the sheet
            df = pd.read_excel(file_path, sheet_name=sheet_name, header=None)
            
            sheet_analysis = {
                'name': sheet_name,
                'rows': len(df),
                'columns': len(df.columns),
                'sample_data': []
            }
            
            print(f"Dimensions: {len(df)} rows x {len(df.columns)} columns")
            
            # Get first 30 rows to understand structure
            print("\nFirst 30 rows:")
            print("-" * 120)
            
            for idx, row in df.head(30).iterrows():
                row_data = []
                for col_idx, val in enumerate(row):
                    if pd.notna(val):
                        row_data.append(f"Col{col_idx}: {val}")
                
                if row_data:
                    print(f"Row {idx}: {' | '.join(row_data[:10])}")  # Show first 10 columns
                    
                    # Store sample data
                    if idx < 30:
                        sheet_analysis['sample_data'].append({
                            'row': idx,
                            'data': [str(v) if pd.notna(v) else '' for v in row.values[:15]]
                        })
            
            # Look for patterns - headers, item codes, amounts
            print("\n\nLooking for patterns...")
            
            # Find rows with "Item" or "Sr" or "No" (likely headers)
            header_rows = []
            for idx, row in df.iterrows():
                row_str = ' '.join([str(v).lower() for v in row.values if pd.notna(v)])
                if any(keyword in row_str for keyword in ['item', 'description', 'quantity', 'rate', 'amount', 'sr.no', 'sr no']):
                    header_rows.append(idx)
                    print(f"Potential header at row {idx}: {[str(v) for v in row.values if pd.notna(v)][:10]}")
            
            sheet_analysis['potential_headers'] = header_rows
            
            # Find numeric columns (likely quantities, rates, amounts)
            numeric_cols = []
            for col_idx in range(len(df.columns)):
                numeric_count = df[col_idx].apply(lambda x: isinstance(x, (int, float)) and pd.notna(x)).sum()
                if numeric_count > 5:  # At least 5 numeric values
                    numeric_cols.append(col_idx)
            
            print(f"\nNumeric columns (likely quantities/rates/amounts): {numeric_cols}")
            sheet_analysis['numeric_columns'] = numeric_cols
            
            file_analysis['sheets'].append(sheet_analysis)
        
        analysis_results[file_name] = file_analysis
        
    except Exception as e:
        print(f"Error analyzing {file_name}: {str(e)}")
        analysis_results[file_name] = {'error': str(e)}

# Save analysis to JSON
output_file = r"E:\DSR\estimation\estimate_analysis.json"
with open(output_file, 'w', encoding='utf-8') as f:
    json.dump(analysis_results, f, indent=2, ensure_ascii=False)

print(f"\n\n{'='*80}")
print(f"Analysis complete! Results saved to: {output_file}")
print(f"{'='*80}")
