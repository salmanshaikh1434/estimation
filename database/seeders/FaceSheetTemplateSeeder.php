<?php

namespace Database\Seeders;

use App\Models\FaceSheetTemplate;
use Illuminate\Database\Seeder;

class FaceSheetTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FaceSheetTemplate::create([
            'name' => 'GMID Default Template',
            'organization_name' => 'GODAVARI MARATHWADA IRRIGATION DEVELOPMENT CORPORATION',
            'command_authority' => 'COMMAND AND DEVELOPMENT AUTHORITY',
            'division_name' => 'Majalgaon Irrigation Division, Parli V',
            'sub_division_name' => 'FCI Irrigation Sub-Division No.10, Parli',
            'executive_engineer' => 'Executive Engineer',
            'fund_head' => 'STATE',
            'major_head' => '4701',
            'minor_head' => '80',
            'service_head' => '800',
            'departmental_head' => '01',
            'header_text' => 'Government of Maharashtra',
            'footer_text' => 'Water Resources Department',
            'is_default' => true,
        ]);

        FaceSheetTemplate::create([
            'name' => 'PWD Standard Template',
            'organization_name' => 'PUBLIC WORKS DEPARTMENT',
            'command_authority' => 'GOVERNMENT OF MAHARASHTRA',
            'division_name' => 'PWD Division',
            'sub_division_name' => 'PWD Sub-Division',
            'executive_engineer' => 'Executive Engineer',
            'fund_head' => 'STATE',
            'major_head' => '5054',
            'minor_head' => '01',
            'service_head' => '101',
            'departmental_head' => '01',
            'header_text' => 'Government of Maharashtra',
            'footer_text' => 'Public Works Department',
            'is_default' => false,
        ]);

        FaceSheetTemplate::create([
            'name' => 'WRD Standard Template',
            'organization_name' => 'WATER RESOURCES DEPARTMENT',
            'command_authority' => 'GOVERNMENT OF MAHARASHTRA',
            'division_name' => 'Water Resources Division',
            'sub_division_name' => 'WRD Sub-Division',
            'executive_engineer' => 'Executive Engineer',
            'fund_head' => 'STATE',
            'major_head' => '4701',
            'minor_head' => '01',
            'service_head' => '101',
            'departmental_head' => '01',
            'header_text' => 'Government of Maharashtra',
            'footer_text' => 'Water Resources Department',
            'is_default' => false,
        ]);
    }
}
