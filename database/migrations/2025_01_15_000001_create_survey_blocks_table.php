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
        Schema::create('survey_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained('survey')->onDelete('cascade');
            $table->string('kode', 20); // A, B, C, D, E, F, G, H
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->integer('urutan');
            $table->boolean('is_terminal')->default(false);
            $table->timestamps();
            $table->softDeletes();
            
            // Ensure unique code per survey
            $table->unique(['survey_id', 'kode']);
            $table->index(['survey_id', 'urutan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_blocks');
    }
};
