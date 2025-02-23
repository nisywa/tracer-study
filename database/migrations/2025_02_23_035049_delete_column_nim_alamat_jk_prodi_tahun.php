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
        Schema::table('alumni', function (Blueprint $table) {
            $table->dropColumn(['nim', 'alamat', 'jenis_kelamin','prodi','tahun_lulus']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            $table->string('nim');
            $table->string('alamat');
            $table->string('jenis_kelamin');
            $table->string('prodi');
            $table->integer('tahun_lulus');
        });
    }
};
