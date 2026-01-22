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
        Schema::create('estimation_measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estimation_item_id')->constrained()->onDelete('cascade');
            $table->integer('row_number')->default(1);
            $table->decimal('length', 10, 3)->nullable();
            $table->decimal('breadth', 10, 3)->nullable(); // Width
            $table->decimal('height', 10, 3)->nullable(); // Depth/Height
            $table->integer('number')->default(1);
            $table->decimal('quantity', 15, 3)->default(0);
            $table->text('remarks')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index('estimation_item_id');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimation_measurements');
    }
};
