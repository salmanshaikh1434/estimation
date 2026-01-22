<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materials = [
            [
                'name' => 'Sand',
                'unit' => 'cum',
                'base_rate' => 0,
                'description' => 'Construction sand for concrete and masonry',
                'is_active' => true,
            ],
            [
                'name' => 'Cement',
                'unit' => 'tonne',
                'base_rate' => 0,
                'description' => 'Portland cement (OPC 43 Grade)',
                'is_active' => true,
            ],
            [
                'name' => 'Coarse Aggregate (Metal)',
                'unit' => 'cum',
                'base_rate' => 0,
                'description' => '20mm and 10mm metal aggregate',
                'is_active' => true,
            ],
            [
                'name' => 'Bricks',
                'unit' => 'nos',
                'base_rate' => 0,
                'description' => 'Common burnt clay bricks',
                'is_active' => true,
            ],
            [
                'name' => 'Steel Reinforcement',
                'unit' => 'tonne',
                'base_rate' => 0,
                'description' => 'TMT bars and reinforcement steel',
                'is_active' => true,
            ],
            [
                'name' => 'Timber',
                'unit' => 'cum',
                'base_rate' => 0,
                'description' => 'Construction timber for formwork',
                'is_active' => true,
            ],
            [
                'name' => 'Gravel (Metal)',
                'unit' => 'cum',
                'base_rate' => 0,
                'description' => '40mm metal for sub-base',
                'is_active' => true,
            ],
            [
                'name' => 'Stone',
                'unit' => 'cum',
                'base_rate' => 0,
                'description' => 'Rubble stone for masonry',
                'is_active' => true,
            ],
        ];

        foreach ($materials as $material) {
            Material::create($material);
        }

        $this->command->info('Created ' . count($materials) . ' materials');
    }
}
