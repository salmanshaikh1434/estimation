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
        Schema::create('appendix_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Appendix-A", "Appendix-B", "Appendix-C"
            $table->string('type'); // 'A', 'B', 'C'
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('sections')->nullable(); // Structured sections
            $table->text('content_template')->nullable(); // HTML/Markdown template
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('type');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appendix_templates');
    }
};
