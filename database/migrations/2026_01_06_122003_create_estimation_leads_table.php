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
        Schema::create('estimation_leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estimation_id')->constrained('estimations')->onDelete('cascade');
            $table->foreignId('material_id')->constrained('materials')->onDelete('cascade');
            $table->string('quarry_location')->nullable();
            $table->decimal('lead_distance_km', 10, 2)->default(0)->comment('Distance in kilometers');
            $table->decimal('lead_rate_per_km', 10, 2)->default(0)->comment('Cost per km from SSR');
            $table->decimal('total_lead_charge', 10, 2)->default(0)->comment('Calculated: distance × rate');
            $table->timestamps();
            
            $table->unique(['estimation_id', 'material_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimation_leads');
    }
};
