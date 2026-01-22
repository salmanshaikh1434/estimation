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
        Schema::create('ssr_rates', function (Blueprint $table) {
            $table->id();
            $table->string('item_code')->nullable();
            $table->text('description')->nullable();
            $table->string('unit', 255)->nullable();
            $table->decimal('rate_non_scheduled', 15, 2)->nullable();
            $table->decimal('rate_scheduled', 15, 2)->nullable();
            $table->string('category')->nullable();
            $table->string('sub_category')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            $table->index('category');
            $table->index('item_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ssr_rates');
    }
};
