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
            $table->string('nip');
            $table->string('email');
            $table->string('jabatan');
            $table->string('satuan_kerja');
            $table->string('unit_kerja');
            $table->string('kepala_bps');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            $table->dropColumn('nip','email','jabatan','satuan_kerja','unit_kerja','kepala_bps');
        });
    }
};
