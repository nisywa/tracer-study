<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Survey;
use App\Models\SurveyBlock;
use App\Models\TemplatePertanyaan;
use App\Models\TemplateJawaban;
use Illuminate\Support\Facades\DB;

echo "🚀 Creating dummy survey data...\n\n";

try {
    DB::beginTransaction();

    // 1. Create or find a survey
    $survey = Survey::firstOrCreate(
        ['nama' => 'Test Survey Navigation'],
        [
            'deskripsi' => 'Survey untuk testing navigation system',
            'tanggal_mulai' => now(),
            'tanggal_selesai' => now()->addDays(30),
            'type_survei' => 'alumni', // Add this required field
            'created_by' => 1
        ]
    );

    echo "✅ Survey created/found: ID {$survey->id} - {$survey->nama}\n";

    // 2. Clear existing blocks for this survey
    SurveyBlock::where('survey_id', $survey->id)->delete();
    echo "🧹 Cleared existing blocks\n";

    // 3. Create Survey Blocks
    $blocks = [
        [
            'kode' => 'PRIBADI',
            'nama' => 'Data Pribadi',
            'deskripsi' => 'Informasi pribadi responden',
            'urutan' => 1,
            'navigation_type' => 'next'
        ],
        [
            'kode' => 'PENDIDIKAN',
            'nama' => 'Pendidikan',
            'deskripsi' => 'Riwayat pendidikan',
            'urutan' => 2,
            'navigation_type' => 'conditional'
        ],
        [
            'kode' => 'PEKERJAAN',
            'nama' => 'Pekerjaan',
            'deskripsi' => 'Status pekerjaan saat ini',
            'urutan' => 3,
            'navigation_type' => 'conditional'
        ],
        [
            'kode' => 'PENGANGGURAN',
            'nama' => 'Pengangguran',
            'deskripsi' => 'Informasi untuk yang belum bekerja',
            'urutan' => 4,
            'navigation_type' => 'next'
        ],
        [
            'kode' => 'EVALUASI',
            'nama' => 'Evaluasi',
            'deskripsi' => 'Evaluasi program studi',
            'urutan' => 5,
            'navigation_type' => 'end'
        ]
    ];

    $createdBlocks = [];
    foreach ($blocks as $blockData) {
        $block = SurveyBlock::create([
            'survey_id' => $survey->id,
            'kode' => $blockData['kode'],
            'nama' => $blockData['nama'],
            'deskripsi' => $blockData['deskripsi'],
            'urutan' => $blockData['urutan'],
            'navigation_type' => $blockData['navigation_type']
        ]);
        $createdBlocks[] = $block;
        echo "📋 Block created: {$block->nama} (ID: {$block->id})\n";
    }

    // 4. Create Questions and Answers

    // BLOCK 1: Data Pribadi
    $block1 = $createdBlocks[0];

    $q1 = TemplatePertanyaan::create([
        'id_survey' => $survey->id,
        'block_id' => $block1->id,
        'pertanyaan' => 'Nama Lengkap',
        'deskripsi_pertanyaan' => 'Masukkan nama lengkap Anda',
        'tipe' => 'text',
        'is_required' => true,
        'urutan' => 1
    ]);

    $q2 = TemplatePertanyaan::create([
        'id_survey' => $survey->id,
        'block_id' => $block1->id,
        'pertanyaan' => 'Jenis Kelamin',
        'deskripsi_pertanyaan' => 'Pilih jenis kelamin Anda',
        'tipe' => 'radio',
        'is_required' => true,
        'urutan' => 2
    ]);

    // Options for Jenis Kelamin
    TemplateJawaban::create([
        'id_template_pertanyaan' => $q2->id,
        'pilihan_jawaban' => 'Laki-laki',
        'navigation_target' => null, // normal flow
        'urutan' => 1
    ]);

    TemplateJawaban::create([
        'id_template_pertanyaan' => $q2->id,
        'pilihan_jawaban' => 'Perempuan',
        'navigation_target' => null, // normal flow
        'urutan' => 2
    ]);

    // BLOCK 2: Pendidikan
    $block2 = $createdBlocks[1];

    $q3 = TemplatePertanyaan::create([
        'id_survey' => $survey->id,
        'block_id' => $block2->id,
        'pertanyaan' => 'Program Studi',
        'deskripsi_pertanyaan' => 'Program studi yang Anda ambil',
        'tipe' => 'select',
        'is_required' => true,
        'urutan' => 1
    ]);

    // Options for Program Studi
    TemplateJawaban::create([
        'id_template_pertanyaan' => $q3->id,
        'pilihan_jawaban' => 'Teknik Informatika',
        'navigation_target' => null,
        'urutan' => 1
    ]);

    TemplateJawaban::create([
        'id_template_pertanyaan' => $q3->id,
        'pilihan_jawaban' => 'Sistem Informasi',
        'navigation_target' => null,
        'urutan' => 2
    ]);

    TemplateJawaban::create([
        'id_template_pertanyaan' => $q3->id,
        'pilihan_jawaban' => 'Manajemen',
        'navigation_target' => null,
        'urutan' => 3
    ]);

    $q4 = TemplatePertanyaan::create([
        'id_survey' => $survey->id,
        'block_id' => $block2->id,
        'pertanyaan' => 'Status Pekerjaan Saat Ini',
        'deskripsi_pertanyaan' => 'Bagaimana status pekerjaan Anda saat ini?',
        'tipe' => 'radio',
        'is_required' => true,
        'urutan' => 2
    ]);

    // Options for Status Pekerjaan (WITH NAVIGATION LOGIC)
    TemplateJawaban::create([
        'id_template_pertanyaan' => $q4->id,
        'pilihan_jawaban' => 'Bekerja (Full-time)',
        'navigation_target' => $createdBlocks[2]->id, // Jump to Pekerjaan block
        'urutan' => 1
    ]);

    TemplateJawaban::create([
        'id_template_pertanyaan' => $q4->id,
        'pilihan_jawaban' => 'Bekerja (Part-time)',
        'navigation_target' => $createdBlocks[2]->id, // Jump to Pekerjaan block
        'urutan' => 2
    ]);

    TemplateJawaban::create([
        'id_template_pertanyaan' => $q4->id,
        'pilihan_jawaban' => 'Belum Bekerja',
        'navigation_target' => $createdBlocks[3]->id, // Jump to Pengangguran block
        'urutan' => 3
    ]);

    TemplateJawaban::create([
        'id_template_pertanyaan' => $q4->id,
        'pilihan_jawaban' => 'Wiraswasta',
        'navigation_target' => $createdBlocks[2]->id, // Jump to Pekerjaan block
        'urutan' => 4
    ]);

    // BLOCK 3: Pekerjaan
    $block3 = $createdBlocks[2];

    $q5 = TemplatePertanyaan::create([
        'id_survey' => $survey->id,
        'block_id' => $block3->id,
        'pertanyaan' => 'Nama Perusahaan',
        'deskripsi_pertanyaan' => 'Nama perusahaan tempat Anda bekerja',
        'tipe' => 'text',
        'is_required' => true,
        'urutan' => 1
    ]);

    $q6 = TemplatePertanyaan::create([
        'id_survey' => $survey->id,
        'block_id' => $block3->id,
        'pertanyaan' => 'Gaji Per Bulan',
        'deskripsi_pertanyaan' => 'Range gaji per bulan (Rupiah)',
        'tipe' => 'select',
        'is_required' => true,
        'urutan' => 2
    ]);

    // Options for Gaji
    $gajiOptions = [
        '< 3 juta',
        '3 - 5 juta',
        '5 - 8 juta',
        '8 - 12 juta',
        '> 12 juta'
    ];

    foreach ($gajiOptions as $index => $gaji) {
        TemplateJawaban::create([
            'id_template_pertanyaan' => $q6->id,
            'pilihan_jawaban' => $gaji,
            'navigation_target' => $createdBlocks[4]->id, // All go to Evaluasi
            'urutan' => $index + 1
        ]);
    }

    // BLOCK 4: Pengangguran
    $block4 = $createdBlocks[3];

    $q7 = TemplatePertanyaan::create([
        'id_survey' => $survey->id,
        'block_id' => $block4->id,
        'pertanyaan' => 'Alasan Belum Bekerja',
        'deskripsi_pertanyaan' => 'Apa alasan utama Anda belum bekerja?',
        'tipe' => 'checkbox',
        'is_required' => true,
        'urutan' => 1
    ]);

    // Options for Alasan Belum Bekerja
    $alasanOptions = [
        'Masih mencari pekerjaan yang sesuai',
        'Melanjutkan pendidikan',
        'Mengurus keluarga',
        'Kondisi kesehatan',
        'Membangun bisnis sendiri'
    ];

    foreach ($alasanOptions as $index => $alasan) {
        TemplateJawaban::create([
            'id_template_pertanyaan' => $q7->id,
            'pilihan_jawaban' => $alasan,
            'navigation_target' => null, // normal flow to next question
            'urutan' => $index + 1
        ]);
    }

    $q8 = TemplatePertanyaan::create([
        'id_survey' => $survey->id,
        'block_id' => $block4->id,
        'pertanyaan' => 'Lamanya Mencari Kerja',
        'deskripsi_pertanyaan' => 'Berapa lama Anda mencari pekerjaan?',
        'tipe' => 'radio',
        'is_required' => true,
        'urutan' => 2
    ]);

    // Options for Lama Mencari Kerja
    $lamaOptions = [
        '< 3 bulan' => null,
        '3 - 6 bulan' => null,
        '6 - 12 bulan' => null,
        '> 12 bulan' => null,
        'Tidak mencari kerja' => $createdBlocks[4]->id // Skip to Evaluasi
    ];

    $urutan = 1;
    foreach ($lamaOptions as $lama => $target) {
        TemplateJawaban::create([
            'id_template_pertanyaan' => $q8->id,
            'pilihan_jawaban' => $lama,
            'navigation_target' => $target ?: $createdBlocks[4]->id, // All go to Evaluasi
            'urutan' => $urutan++
        ]);
    }

    // BLOCK 5: Evaluasi
    $block5 = $createdBlocks[4];

    $q9 = TemplatePertanyaan::create([
        'id_survey' => $survey->id,
        'block_id' => $block5->id,
        'pertanyaan' => 'Rating Program Studi',
        'deskripsi_pertanyaan' => 'Berikan rating untuk program studi Anda (1-5)',
        'tipe' => 'radio',
        'is_required' => true,
        'urutan' => 1
    ]);

    // Options for Rating
    for ($i = 1; $i <= 5; $i++) {
        TemplateJawaban::create([
            'id_template_pertanyaan' => $q9->id,
            'pilihan_jawaban' => (string)$i,
            'navigation_target' => 'end', // End survey
            'urutan' => $i
        ]);
    }

    $q10 = TemplatePertanyaan::create([
        'id_survey' => $survey->id,
        'block_id' => $block5->id,
        'pertanyaan' => 'Saran dan Masukan',
        'deskripsi_pertanyaan' => 'Berikan saran untuk perbaikan program studi',
        'tipe' => 'textarea',
        'is_required' => false,
        'urutan' => 2
    ]);

    DB::commit();

    echo "\n🎉 SUCCESS! Dummy survey data created:\n";
    echo "📊 Survey ID: {$survey->id}\n";
    echo "📋 Blocks: " . count($createdBlocks) . "\n";
    echo "❓ Questions: " . TemplatePertanyaan::where('id_survey', $survey->id)->count() . "\n";
    echo "✅ Answers: " . TemplateJawaban::whereIn('id_template_pertanyaan',
        TemplatePertanyaan::where('id_survey', $survey->id)->pluck('id'))->count() . "\n";

    echo "\n🔗 Navigation Flow:\n";
    echo "1. Data Pribadi → 2. Pendidikan\n";
    echo "2. Status Pekerjaan:\n";
    echo "   - Bekerja → 3. Pekerjaan → 5. Evaluasi\n";
    echo "   - Belum Bekerja → 4. Pengangguran → 5. Evaluasi\n";
    echo "   - Tidak mencari kerja → 5. Evaluasi (langsung)\n";
    echo "5. Evaluasi → END\n";

    echo "\n🌐 Test URL: http://localhost/user/survey/{$survey->id}\n";

} catch (Exception $e) {
    DB::rollback();
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "📍 Line: " . $e->getLine() . "\n";
    echo "📄 File: " . $e->getFile() . "\n";
}
