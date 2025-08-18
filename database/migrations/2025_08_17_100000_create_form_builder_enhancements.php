<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update survey_blocks untuk form builder (tabel sudah ada)
        if (Schema::hasTable('survey_blocks')) {
            Schema::table('survey_blocks', function (Blueprint $table) {
                // Ubah kode untuk mendukung "Section 1", "Section 2", etc
                if (Schema::hasColumn('survey_blocks', 'kode')) {
                    $table->string('kode', 50)->change(); // dari 20 ke 50 chars
                }

                // Tambah kolom untuk section navigation jika belum ada
                if (!Schema::hasColumn('survey_blocks', 'navigation_type')) {
                    $table->enum('navigation_type', ['next', 'jump', 'end'])->default('next')->after('is_terminal');
                }

                if (!Schema::hasColumn('survey_blocks', 'target_section_id')) {
                    $table->unsignedBigInteger('target_section_id')->nullable()->after('navigation_type');
                    $table->foreign('target_section_id')->references('id')->on('survey_blocks')->onDelete('set null');
                }

                // Tambah metadata untuk form builder
                if (!Schema::hasColumn('survey_blocks', 'metadata')) {
                    $table->json('metadata')->nullable()->after('target_section_id');
                }
            });
        } else {
            // Jika tabel survey_blocks belum ada, buat baru
            Schema::create('survey_blocks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('survey_id')->constrained('survey')->onDelete('cascade');
                $table->string('kode', 50); // Section 1, Section 2, etc
                $table->string('nama', 255);
                $table->text('deskripsi')->nullable();
                $table->integer('urutan')->default(1);
                $table->boolean('is_terminal')->default(false);
                $table->enum('navigation_type', ['next', 'jump', 'end'])->default('next');
                $table->unsignedBigInteger('target_section_id')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->foreign('target_section_id')->references('id')->on('survey_blocks')->onDelete('set null');
                $table->index(['survey_id', 'urutan']);
            });
        }

        // 2. Update template_pertanyaan untuk menghubungkan dengan survey_blocks
        if (!Schema::hasColumn('template_pertanyaan', 'block_id')) {
            Schema::table('template_pertanyaan', function (Blueprint $table) {
                $table->foreignId('block_id')->nullable()->after('id_survey')
                      ->constrained('survey_blocks')->onDelete('set null');
            });
        }

        // 3. Tambah kolom is_required jika belum ada
        if (!Schema::hasColumn('template_pertanyaan', 'is_required')) {
            Schema::table('template_pertanyaan', function (Blueprint $table) {
                $table->boolean('is_required')->default(false)->after('urutan');
            });
        }

        // 4. Tabel untuk section navigation rules
        if (!Schema::hasTable('section_navigation_rules')) {
            Schema::create('section_navigation_rules', function (Blueprint $table) {
                $table->id();
                $table->foreignId('survey_id')->constrained('survey')->onDelete('cascade');
                $table->foreignId('source_section_id')->constrained('survey_blocks')->onDelete('cascade');
                $table->enum('navigation_action', ['next', 'jump_to_section', 'end_survey']);
                $table->foreignId('target_section_id')->nullable()->constrained('survey_blocks')->onDelete('cascade');
                $table->json('conditions')->nullable(); // untuk conditional navigation di masa depan
                $table->timestamps();

                $table->index(['survey_id', 'source_section_id']);
            });
        }

        // 5. Drop kolom 'blok' yang tidak digunakan jika ada
        if (Schema::hasColumn('template_pertanyaan', 'blok')) {
            Schema::table('template_pertanyaan', function (Blueprint $table) {
                $table->dropColumn('blok');
            });
        }
    }

    public function down(): void
    {
        // Drop foreign keys first
        if (Schema::hasTable('survey_blocks')) {
            Schema::table('survey_blocks', function (Blueprint $table) {
                if (Schema::hasColumn('survey_blocks', 'target_section_id')) {
                    $table->dropForeign(['target_section_id']);
                    $table->dropColumn(['navigation_type', 'target_section_id', 'metadata']);
                }

                if (Schema::hasColumn('survey_blocks', 'kode')) {
                    $table->string('kode', 20)->change();
                }
            });
        }

        Schema::dropIfExists('section_navigation_rules');

        if (Schema::hasColumn('template_pertanyaan', 'block_id')) {
            Schema::table('template_pertanyaan', function (Blueprint $table) {
                $table->dropForeign(['block_id']);
                $table->dropColumn('block_id');
            });
        }

        if (Schema::hasColumn('template_pertanyaan', 'is_required')) {
            Schema::table('template_pertanyaan', function (Blueprint $table) {
                $table->dropColumn('is_required');
            });
        }

        // Add back 'blok' column if needed for rollback
        if (!Schema::hasColumn('template_pertanyaan', 'blok')) {
            Schema::table('template_pertanyaan', function (Blueprint $table) {
                $table->string('blok')->nullable()->after('urutan');
            });
        }
    }
};
