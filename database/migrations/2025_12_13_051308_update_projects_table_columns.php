<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Rename columns to match our controller/frontend
            $table->renameColumn('project_name', 'name');
            $table->renameColumn('project_code', 'code');
            $table->renameColumn('client_name', 'client');
            
            // Add missing columns for face sheet details
            $table->foreignId('face_sheet_template_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
            $table->string('sanctioned_estimate_number')->nullable()->after('end_date');
            $table->string('financial_year')->nullable()->after('sanctioned_estimate_number');
            $table->string('prepared_by')->nullable()->after('financial_year');
            $table->string('checked_by')->nullable()->after('prepared_by');
            $table->string('approved_by')->nullable()->after('checked_by');
            
            // Update status enum to match our needs
            $table->dropColumn('status');
        });
        
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('status', ['draft', 'active', 'completed', 'on_hold'])->default('draft')->after('end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Rename back
            $table->renameColumn('name', 'project_name');
            $table->renameColumn('code', 'project_code');
            $table->renameColumn('client', 'client_name');
            
            // Drop added columns
            $table->dropForeign(['face_sheet_template_id']);
            $table->dropColumn([
                'face_sheet_template_id',
                'sanctioned_estimate_number',
                'financial_year',
                'prepared_by',
                'checked_by',
                'approved_by',
            ]);
            
            // Restore original status
            $table->dropColumn('status');
        });
        
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('status', ['draft', 'active', 'completed', 'archived'])->default('draft');
        });
    }
};
