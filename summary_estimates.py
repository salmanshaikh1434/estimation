import json

with open('estimate_analysis.json', encoding='utf-8') as f:
    data = json.load(f)

print("\n" + "="*80)
print("SUMMARY OF ALL ESTIMATE FILES")
print("="*80 + "\n")

for i, (filename, file_data) in enumerate(data.items(), 1):
    if 'sheets' in file_data:
        print(f"{i}. {filename}")
        print(f"   File Size: {file_data['file_size']:,} bytes")
        print(f"   Number of Sheets: {len(file_data['sheets'])}")
        print(f"   Sheet Names:")
        for sheet in file_data['sheets']:
            print(f"      - {sheet['name']} ({sheet['rows']} rows x {sheet['columns']} cols)")
            if sheet['potential_headers']:
                print(f"        Headers found at rows: {sheet['potential_headers']}")
        print()
