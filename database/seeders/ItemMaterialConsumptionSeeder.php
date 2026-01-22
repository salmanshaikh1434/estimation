<?php

namespace Database\Seeders;

use App\Models\ItemMaterialConsumption;
use App\Models\Material;
use App\Models\SsrRate;
use Illuminate\Database\Seeder;

class ItemMaterialConsumptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder links SSR items to materials with consumption factors
     * Based on standard construction consumption rates
     */
    public function run(): void
    {
        // Get materials
        $sand = Material::where('name', 'Sand')->first();
        $cement = Material::where('name', 'Cement')->first();
        $metal = Material::where('name', 'Coarse Aggregate (Metal)')->first();
        $bricks = Material::where('name', 'Bricks')->first();
        $steel = Material::where('name', 'Steel Reinforcement')->first();
        $gravel = Material::where('name', 'Gravel (Metal)')->first();
        $stone = Material::where('name', 'Stone')->first();

        if (!$sand || !$cement || !$metal) {
            $this->command->error('Materials not found! Run MaterialSeeder first.');
            return;
        }

        // Define consumption factors for common concrete grades
        $concreteConsumptions = [
            // M10 Concrete (1:3:6)
            'M10' => [
                'sand' => 0.47,      // 0.47 cum sand per cum concrete
                'cement' => 0.25,    // 0.25 tonne cement per cum concrete
                'metal' => 0.85,     // 0.85 cum metal per cum concrete
            ],
            // M15 Concrete (1:2:4)
            'M15' => [
                'sand' => 0.45,
                'cement' => 0.31,
                'metal' => 0.82,
            ],
            // M20 Concrete (1:1.5:3)
            'M20' => [
                'sand' => 0.42,
                'cement' => 0.35,
                'metal' => 0.78,
            ],
            // M25 Concrete
            'M25' => [
                'sand' => 0.40,
                'cement' => 0.40,
                'metal' => 0.75,
            ],
        ];

        $count = 0;

        // Find and link concrete items
        foreach ($concreteConsumptions as $grade => $consumption) {
            // Search for SSR items containing this grade
            $ssrItems = SsrRate::where('description', 'like', "%{$grade}%")
                ->where('description', 'like', '%concrete%')
                ->orWhere('description', 'like', '%Concrete%')
                ->orWhere('item_code', 'like', "%{$grade}%")
                ->limit(5)
                ->get();

            foreach ($ssrItems as $item) {
                // Add sand consumption
                ItemMaterialConsumption::create([
                    'ssr_rate_id' => $item->id,
                    'material_id' => $sand->id,
                    'consumption_factor' => $consumption['sand'],
                ]);
                $count++;

                // Add cement consumption
                ItemMaterialConsumption::create([
                    'ssr_rate_id' => $item->id,
                    'material_id' => $cement->id,
                    'consumption_factor' => $consumption['cement'],
                ]);
                $count++;

                // Add metal consumption
                ItemMaterialConsumption::create([
                    'ssr_rate_id' => $item->id,
                    'material_id' => $metal->id,
                    'consumption_factor' => $consumption['metal'],
                ]);
                $count++;
            }
        }

        // Add masonry items (brick work)
        if ($bricks) {
            $masonryItems = SsrRate::where('description', 'like', '%brick%')
                ->orWhere('description', 'like', '%masonry%')
                ->limit(10)
                ->get();

            foreach ($masonryItems as $item) {
                // Bricks: ~500 bricks per cum of masonry
                ItemMaterialConsumption::create([
                    'ssr_rate_id' => $item->id,
                    'material_id' => $bricks->id,
                    'consumption_factor' => 500,
                ]);
                $count++;

                // Mortar uses sand and cement
                ItemMaterialConsumption::create([
                    'ssr_rate_id' => $item->id,
                    'material_id' => $sand->id,
                    'consumption_factor' => 0.25,
                ]);
                $count++;

                ItemMaterialConsumption::create([
                    'ssr_rate_id' => $item->id,
                    'material_id' => $cement->id,
                    'consumption_factor' => 0.08,
                ]);
                $count++;
            }
        }

        // Add road work items (if WRD items exist)
        if ($gravel) {
            $roadItems = SsrRate::where('description', 'like', '%road%')
                ->orWhere('description', 'like', '%pavement%')
                ->orWhere('description', 'like', '%sub-base%')
                ->limit(5)
                ->get();

            foreach ($roadItems as $item) {
                ItemMaterialConsumption::create([
                    'ssr_rate_id' => $item->id,
                    'material_id' => $gravel->id,
                    'consumption_factor' => 1.20, // 1.2 cum metal per cum compacted
                ]);
                $count++;
            }
        }

        $this->command->info("Created {$count} material consumption records");
    }
}
