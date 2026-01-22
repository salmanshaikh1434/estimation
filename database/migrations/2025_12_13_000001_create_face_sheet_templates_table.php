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
        Schema::create('face_sheet_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('organization_name');
            $table->string('command_authority')->nullable();
            $table->string('division_name');
            $table->string('sub_division_name')->nullable();
            $table->string('executive_engineer')->nullable();
            $table->string('fund_head')->default('STATE');
            $table->string('major_head')->nullable();
            $table->string('minor_head')->nullable();
            $table->string('service_head')->nullable();
            $table->string('departmental_head')->nullable();
            $table->text('header_text')->nullable();
            $table->text('footer_text')->nullable();
            $table->string('logo_path')->nullable();
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
        Schema::dropIfExists('face_sheet_templates');
    }
};
