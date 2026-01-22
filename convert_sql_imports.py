import re
import os
from datetime import datetime

def convert_sql_to_new_schema(input_file, output_file, target_table):
    """
    Convert SQL file from old dsr_master schema to new rate table schema.
    """
    print(f"\n{'='*80}")
    print(f"Converting: {os.path.basename(input_file)}")
    print(f"Target table: {target_table}")
    print(f"{'='*80}\n")
    
    with open(input_file, 'r', encoding='utf-8') as f:
        lines = f.readlines()
    
    output_lines = []
    
    # Add header
    output_lines.append(f"-- {target_table.upper()} Import\n")
    output_lines.append(f"-- Converted from: {os.path.basename(input_file)}\n")
    output_lines.append(f"-- Conversion date: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}\n")
    output_lines.append(f"--\n\n")
    
    # Skip CREATE TABLE and TRUNCATE - we already have the table structure
    output_lines.append(f"-- Importing data into {target_table}\n\n")
    
    in_insert = False
    insert_buffer = []
    
    for line in lines:
        # Skip CREATE TABLE statements
        if re.match(r'CREATE TABLE', line, re.IGNORECASE):
            continue
        
        # Skip TRUNCATE statements
        if re.match(r'TRUNCATE TABLE', line, re.IGNORECASE):
            continue
        
        # Skip comments and empty lines at the beginning
        if line.strip().startswith('--') or not line.strip():
            continue
        
        # Process INSERT statements
        if re.match(r'INSERT INTO', line, re.IGNORECASE):
            # Replace table name and column list
            line = re.sub(
                r'INSERT INTO\s+\w+\s*\([^)]+\)',
                f'INSERT INTO {target_table} (item_code, description, unit, rate_scheduled, rate_non_scheduled, category, sub_category)',
                line,
                flags=re.IGNORECASE
            )
            in_insert = True
            insert_buffer = [line]
        elif in_insert:
            insert_buffer.append(line)
            if ';' in line:
                # End of INSERT statement
                full_insert = ''.join(insert_buffer)
                
                # Convert VALUES
                # Old format: ('item_code', 'spec_ref', 'description', 'unit', base_rate, labor_rate, 'chapter')
                # New format: ('item_code', 'description', 'unit', rate_scheduled, rate_non_scheduled, 'category', 'sub_category')
                
                # Find all value tuples
                values_pattern = r"\('([^']*)',\s*'([^']*)',\s*'((?:[^']|'')*)',\s*'([^']*)',\s*([\d.]+|NULL),\s*([\d.]+|NULL),\s*'([^']*)'\)"
                
                def reorder_values(match):
                    item_code = match.group(1).replace("'", "''")
                    spec_ref = match.group(2).replace("'", "''")
                    description = match.group(3)  # Already escaped
                    unit = match.group(4).replace("'", "''")
                    base_rate = match.group(5)
                    labor_rate = match.group(6)
                    chapter = match.group(7).replace("'", "''")
                    
                    # Truncate category if too long (max 255 chars)
                    if len(chapter) > 255:
                        chapter = chapter[:252] + '...'
                    
                    # New order: item_code, description, unit, rate_scheduled, rate_non_scheduled, category, sub_category
                    return f"('{item_code}', '{description}', '{unit}', {base_rate}, {labor_rate}, '{chapter}', '{spec_ref}')"
                
                converted_insert = re.sub(values_pattern, reorder_values, full_insert)
                output_lines.append(converted_insert)
                
                in_insert = False
                insert_buffer = []
    
    # Write output file
    with open(output_file, 'w', encoding='utf-8') as f:
        f.writelines(output_lines)
    
    print(f"✓ Conversion complete!")
    print(f"✓ Output saved to: {output_file}")
    
    # Count records
    record_count = len(re.findall(r'VALUES', ''.join(output_lines), re.IGNORECASE))
    print(f"✓ Total INSERT statements: {record_count}\n")
    
    return record_count


# Main conversion
print("\n" + "="*80)
print("SQL IMPORT FILE CONVERTER")
print("Converting old schema to new three-table structure")
print("="*80)

# Create output directory
os.makedirs(r'E:\DSR\estimation\database\imports', exist_ok=True)

conversions = [
    {
        'input': r'E:\DSR\data\output\dsr_output_import.sql',
        'output': r'E:\DSR\estimation\database\imports\dsr_rates_import.sql',
        'table': 'dsr_rates'
    },
    {
        'input': r'E:\DSR\data\output\ssr_output_import.sql',
        'output': r'E:\DSR\estimation\database\imports\ssr_rates_import.sql',
        'table': 'ssr_rates'
    },
    {
        'input': r'E:\DSR\data\output\wrd_output_import.sql',
        'output': r'E:\DSR\estimation\database\imports\wrd_rates_import.sql',
        'table': 'wrd_rates'
    }
]

total_records = 0

for conv in conversions:
    if os.path.exists(conv['input']):
        try:
            count = convert_sql_to_new_schema(conv['input'], conv['output'], conv['table'])
            total_records += count
        except Exception as e:
            print(f"✗ Error: {str(e)}\n")
    else:
        print(f"✗ File not found: {conv['input']}\n")

print("="*80)
print(f"CONVERSION COMPLETE!")
print(f"Total records to import: {total_records}")
print("="*80)
print("\nConverted files location:")
print("  E:\\DSR\\estimation\\database\\imports\\")
print("\nTo import into MySQL:")
print("  mysql -u root -proot estimation < E:\\DSR\\estimation\\database\\imports\\dsr_rates_import.sql")
print("  mysql -u root -proot estimation < E:\\DSR\\estimation\\database\\imports\\ssr_rates_import.sql")
print("  mysql -u root -proot estimation < E:\\DSR\\estimation\\database\\imports\\wrd_rates_import.sql")
print("\nOr from MySQL prompt:")
print("  USE estimation;")
print("  SOURCE E:\\DSR\\estimation\\database\\imports\\dsr_rates_import.sql")
print("  SOURCE E:\\DSR\\estimation\\database\\imports\\ssr_rates_import.sql")
print("  SOURCE E:\\DSR\\estimation\\database\\imports\\wrd_rates_import.sql")
print("="*80 + "\n")
