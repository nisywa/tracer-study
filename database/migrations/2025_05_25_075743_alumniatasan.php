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
            $table->string('no_hp', 15)->nullable()->change();
            $table->string('jabatan', 255)->nullable()->change();
            $table->string('satuan_kerja', 255)->nullable()->change();
            $table->string('unit_kerja', 255)->nullable()->change();
        });
        Schema::table('atasan', function (Blueprint $table) {
            $table->string('no_hp', 15)->nullable()->change();
            $table->string('jabatan', 255)->nullable()->change();
            $table->string('satuan_kerja', 255)->nullable()->change();
            $table->string('unit_kerja', 255)->nullable()->change();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            $table->string('no_hp', 15)->nullable(false)->change();
            $table->string('jabatan', 255)->nullable(false)->change();
            $table->string('satuan_kerja', 255)->nullable(false)->change();
            $table->string('unit_kerja', 255)->nullable(false)->change();
        });

        Schema::table('atasan', function (Blueprint $table) {
            $table->string('no_hp', 15)->nullable(false)->change();
            $table->string('jabatan', 255)->nullable(false)->change();
            $table->string('satuan_kerja', 255)->nullable(false)->change();
            $table->string('unit_kerja', 255)->nullable(false)->change();
        });
    }
};
