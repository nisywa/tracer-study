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
        Schema::create('survey_user_jawaban', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_user_id')->constrained('survey_user')->onDelete('cascade');
            $table->text('jawaban');
            $table->foreignId('template_pertanyaan_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_user_jawaban');
    }
};
