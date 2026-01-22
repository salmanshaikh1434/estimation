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
        Schema::create('estimation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estimation_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('rate_id');
            $table->enum('rate_type', ['dsr', 'ssr', 'wrd', 'custom'])->default('dsr');
            $table->decimal('quantity', 15, 3);
            $table->decimal('rate', 15, 2);
            $table->decimal('amount', 15, 2);
            $table->text('remarks')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index('estimation_id');
            $table->index(['rate_id', 'rate_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimation_items');
    }
};
