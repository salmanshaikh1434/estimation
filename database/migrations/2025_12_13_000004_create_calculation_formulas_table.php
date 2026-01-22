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
        Schema::create('calculation_formulas', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Excavation - Volume", "Plastering - Area"
            $table->string('code')->unique(); // e.g., "EXC_VOL", "PLAST_AREA"
            $table->enum('category', ['earthwork', 'concrete', 'masonry', 'finishing', 'steel', 'custom'])->default('custom');
            $table->enum('calculation_type', ['simple', 'area', 'volume', 'perimeter', 'circumference', 'custom'])->default('simple');
            $table->text('description')->nullable();
            $table->text('formula'); // e.g., "length * width * height * rate"
            $table->json('parameters'); // Required parameters with types and labels
            $table->json('validation_rules')->nullable(); // Validation rules for parameters
            $table->string('unit')->nullable(); // Default unit (Cu.m, Sq.m, etc.)
            $table->text('example')->nullable(); // Example calculation
            $table->boolean('is_active')->default(true);
            $table->integer('usage_count')->default(0); // Track usage
            $table->timestamps();
            
            $table->index('code');
            $table->index('category');
            $table->index('calculation_type');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calculation_formulas');
    }
};
