<?php

namespace Database\Seeders;

use App\Models\CalculationFormula;
use Illuminate\Database\Seeder;

class CalculationFormulaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $formulas = [
            [
                'name' => 'Simple Quantity × Rate',
                'code' => 'SIMPLE',
                'category' => 'custom',
                'calculation_type' => 'simple',
                'description' => 'Basic calculation: Quantity multiplied by Rate',
                'formula' => 'quantity * rate',
                'parameters' => [
                    ['name' => 'quantity', 'label' => 'Quantity', 'type' => 'number', 'required' => true],
                ],
                'validation_rules' => [
                    'quantity' => 'required|numeric|min:0.001',
                ],
                'unit' => 'Various',
                'example' => 'Quantity: 100, Rate: 25.50 = 2,550.00',
                'is_active' => true,
            ],
            [
                'name' => 'Area Calculation (L × W)',
                'code' => 'AREA_LW',
                'category' => 'finishing',
                'calculation_type' => 'area',
                'description' => 'Calculate area: Length × Width × Rate',
                'formula' => 'length * width * rate',
                'parameters' => [
                    ['name' => 'length', 'label' => 'Length (m)', 'type' => 'number', 'required' => true],
                    ['name' => 'width', 'label' => 'Width (m)', 'type' => 'number', 'required' => true],
                ],
                'validation_rules' => [
                    'length' => 'required|numeric|min:0.01',
                    'width' => 'required|numeric|min:0.01',
                ],
                'unit' => 'Sq.m',
                'example' => 'Length: 10m, Width: 5m, Rate: 150 = 7,500.00',
                'is_active' => true,
            ],
            [
                'name' => 'Volume Calculation (L × W × H)',
                'code' => 'VOLUME_LWH',
                'category' => 'earthwork',
                'calculation_type' => 'volume',
                'description' => 'Calculate volume: Length × Width × Height × Rate',
                'formula' => 'length * width * height * rate',
                'parameters' => [
                    ['name' => 'length', 'label' => 'Length (m)', 'type' => 'number', 'required' => true],
                    ['name' => 'width', 'label' => 'Width (m)', 'type' => 'number', 'required' => true],
                    ['name' => 'height', 'label' => 'Height/Depth (m)', 'type' => 'number', 'required' => true],
                ],
                'validation_rules' => [
                    'length' => 'required|numeric|min:0.01',
                    'width' => 'required|numeric|min:0.01',
                    'height' => 'required|numeric|min:0.01',
                ],
                'unit' => 'Cu.m',
                'example' => 'Length: 10m, Width: 5m, Height: 3m, Rate: 5500 = 825,000.00',
                'is_active' => true,
            ],
            [
                'name' => 'Perimeter Calculation 2(L + W) × H',
                'code' => 'PERIMETER',
                'category' => 'masonry',
                'calculation_type' => 'perimeter',
                'description' => 'Calculate perimeter area: 2(Length + Width) × Height × Rate',
                'formula' => '2 * (length + width) * height * rate',
                'parameters' => [
                    ['name' => 'length', 'label' => 'Length (m)', 'type' => 'number', 'required' => true],
                    ['name' => 'width', 'label' => 'Width (m)', 'type' => 'number', 'required' => true],
                    ['name' => 'height', 'label' => 'Height (m)', 'type' => 'number', 'required' => true],
                ],
                'validation_rules' => [
                    'length' => 'required|numeric|min:0.01',
                    'width' => 'required|numeric|min:0.01',
                    'height' => 'required|numeric|min:0.01',
                ],
                'unit' => 'Sq.m',
                'example' => 'Length: 10m, Width: 5m, Height: 3m, Rate: 250 = 22,500.00',
                'is_active' => true,
            ],
            [
                'name' => 'Circular Area (π × r²)',
                'code' => 'CIRCLE_AREA',
                'category' => 'concrete',
                'calculation_type' => 'custom',
                'description' => 'Calculate circular area: π × radius² × Rate',
                'formula' => '3.14159 * radius * radius * rate',
                'parameters' => [
                    ['name' => 'radius', 'label' => 'Radius (m)', 'type' => 'number', 'required' => true],
                ],
                'validation_rules' => [
                    'radius' => 'required|numeric|min:0.01',
                ],
                'unit' => 'Sq.m',
                'example' => 'Radius: 5m, Rate: 500 = 39,269.75',
                'is_active' => true,
            ],
            [
                'name' => 'Excavation with Deduction',
                'code' => 'EXC_DEDUCT',
                'category' => 'earthwork',
                'calculation_type' => 'custom',
                'description' => 'Excavation volume with deduction: (Total - Deduction) × Width × Depth × Rate',
                'formula' => '((total_length - deduction_length) * width * depth) * rate',
                'parameters' => [
                    ['name' => 'total_length', 'label' => 'Total Length (m)', 'type' => 'number', 'required' => true],
                    ['name' => 'deduction_length', 'label' => 'Deduction Length (m)', 'type' => 'number', 'required' => false],
                    ['name' => 'width', 'label' => 'Width (m)', 'type' => 'number', 'required' => true],
                    ['name' => 'depth', 'label' => 'Depth (m)', 'type' => 'number', 'required' => true],
                ],
                'validation_rules' => [
                    'total_length' => 'required|numeric|min:0.01',
                    'deduction_length' => 'nullable|numeric|min:0',
                    'width' => 'required|numeric|min:0.01',
                    'depth' => 'required|numeric|min:0.01',
                ],
                'unit' => 'Cu.m',
                'example' => 'Total: 100m, Deduction: 10m, Width: 2m, Depth: 1.5m, Rate: 25.50 = 6,885.00',
                'is_active' => true,
            ],
            [
                'name' => 'Steel Weight (Length × Unit Weight)',
                'code' => 'STEEL_WEIGHT',
                'category' => 'steel',
                'calculation_type' => 'custom',
                'description' => 'Calculate steel weight: Length × Unit Weight × Rate',
                'formula' => 'length * unit_weight * rate',
                'parameters' => [
                    ['name' => 'length', 'label' => 'Length (m)', 'type' => 'number', 'required' => true],
                    ['name' => 'unit_weight', 'label' => 'Unit Weight (kg/m)', 'type' => 'number', 'required' => true],
                ],
                'validation_rules' => [
                    'length' => 'required|numeric|min:0.01',
                    'unit_weight' => 'required|numeric|min:0.01',
                ],
                'unit' => 'Kg',
                'example' => 'Length: 100m, Unit Weight: 0.888 kg/m, Rate: 55 = 4,884.00',
                'is_active' => true,
            ],
        ];

        foreach ($formulas as $formula) {
            CalculationFormula::create($formula);
        }
    }
}
