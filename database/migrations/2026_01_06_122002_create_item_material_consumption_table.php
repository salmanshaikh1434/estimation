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
        Schema::create('item_material_consumption', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ssr_rate_id')->constrained('ssr_rates')->onDelete('cascade');
            $table->foreignId('material_id')->constrained('materials')->onDelete('cascade');
            $table->decimal('consumption_factor', 10, 4)->comment('Material consumption per unit of item');
            $table->timestamps();
            
            $table->unique(['ssr_rate_id', 'material_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_material_consumption');
    }
};
