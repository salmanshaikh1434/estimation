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
        Schema::table('estimations', function (Blueprint $table) {
            // Rename columns
            $table->renameColumn('estimation_name', 'name');
            $table->dropColumn('estimation_code');
            $table->renameColumn('subtotal', 'sub_total');
            
            // Drop old rate_type and status
            $table->dropColumn('rate_type');
            $table->dropColumn('status');
            
            // Drop tax columns (we'll use different calculation)
            $table->dropColumn('tax_percentage');
            $table->dropColumn('tax_amount');
        });
        
        Schema::table('estimations', function (Blueprint $table) {
            // Add user_id
            $table->foreignId('user_id')->after('project_id')->constrained()->onDelete('cascade');
            
            // Add new rate_type with correct values
            $table->enum('rate_type', ['dsr', 'ssr', 'wrd', 'mixed'])->default('dsr')->after('description');
            
            // Add calculation parameters
            $table->decimal('royalty_amount', 15, 2)->default(0)->after('rate_type');
            $table->decimal('contingency_percentage', 5, 2)->default(3)->after('royalty_amount');
            $table->decimal('gst_percentage', 5, 2)->default(18)->after('contingency_percentage');
            
            // Add new status with correct values
            $table->enum('status', ['draft', 'final'])->default('draft')->after('total_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estimations', function (Blueprint $table) {
            // Rename back
            $table->renameColumn('name', 'estimation_name');
            $table->renameColumn('sub_total', 'subtotal');
            
            // Drop new columns
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id',
                'royalty_amount',
                'contingency_percentage',
                'gst_percentage',
            ]);
            
            // Drop new enums
            $table->dropColumn('rate_type');
            $table->dropColumn('status');
        });
        
        Schema::table('estimations', function (Blueprint $table) {
            // Add back old columns
            $table->string('estimation_code')->unique();
            $table->enum('rate_type', ['scheduled', 'non_scheduled'])->default('scheduled');
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
        });
    }
};
