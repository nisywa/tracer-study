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
        Schema::create('template_pertanyaan', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('id_survey')->constrained('surveys')->onDelete('cascade');
            $table->string('pertanyaan');
            $table->string('tipe')->comment('text, textarea, radio, checkbox, select, file');
            $table->integer('urutan');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_pertanyaan');
    }
};
