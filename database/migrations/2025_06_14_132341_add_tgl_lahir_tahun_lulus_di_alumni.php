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
            $table->string('tanggal_lahir');
            $table->string('tahun_lulus')->nullable();
            $table->string('email')->nullable()->change();
            $table->string('nip')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            $table->dropColumn('tanggal_lahir', 'tahun_lulus');
            $table->string('email')->nullable(false)->change();
            $table->string('nip')->nullable(false)->change();
        });
    }
};
