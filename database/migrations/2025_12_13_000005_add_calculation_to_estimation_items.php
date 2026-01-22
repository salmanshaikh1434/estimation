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
        Schema::table('estimation_items', function (Blueprint $table) {
            $table->foreignId('calculation_formula_id')->nullable()->after('rate_type')->constrained()->onDelete('set null');
            $table->json('calculation_params')->nullable()->after('calculation_formula_id');
            $table->decimal('calculated_quantity', 15, 3)->nullable()->after('calculation_params');
            $table->text('calculation_notes')->nullable()->after('calculated_quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estimation_items', function (Blueprint $table) {
            $table->dropForeign(['calculation_formula_id']);
            $table->dropColumn(['calculation_formula_id', 'calculation_params', 'calculated_quantity', 'calculation_notes']);
        });
    }
};
