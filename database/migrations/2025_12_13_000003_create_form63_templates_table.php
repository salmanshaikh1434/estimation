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
        Schema::create('form63_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('form_number')->default('FORM-63');
            $table->text('description')->nullable();
            $table->json('fields')->nullable(); // Form fields structure
            $table->text('template_content')->nullable(); // HTML template
            $table->string('version')->default('1.0');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            
            $table->index('is_default');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form63_templates');
    }
};
