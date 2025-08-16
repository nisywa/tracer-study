<?php

namespace Database\Seeders;

use App\Models\Survey;
use App\Models\SurveyBlock;
use App\Models\TemplatePertanyaan;
use App\Models\TemplateJawaban;
use App\Models\SurveyBranchRule;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SurveyBlockDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a demo survey
        $survey = Survey::create([
            'nama' => 'Demo Survey dengan Blok dan Percabangan',
            'tanggal_mulai' => Carbon::now(),
            'tanggal_selesai' => Carbon::now()->addMonths(3),
            'type_survei' => 'alumni',
            'deskripsi' => 'Survey demo untuk menunjukkan fitur manajemen blok dan aturan percabangan'
        ]);

        // Create blocks
        $blockA = SurveyBlock::create([
            'survey_id' => $survey->id,
            'kode' => 'A',
            'nama' => 'Informasi Demografis',
            'deskripsi' => 'Pertanyaan dasar tentang responden',
            'urutan' => 1,
            'is_terminal' => false
        ]);

        $blockB = SurveyBlock::create([
            'survey_id' => $survey->id,
            'kode' => 'B',
            'nama' => 'Pengalaman Kerja - Fresh Graduate',
            'deskripsi' => 'Untuk alumni yang baru lulus dan belum bekerja',
            'urutan' => 2,
            'is_terminal' => false
        ]);

        $blockC = SurveyBlock::create([
            'survey_id' => $survey->id,
            'kode' => 'C',
            'nama' => 'Pengalaman Kerja - Sudah Bekerja',
            'deskripsi' => 'Untuk alumni yang sudah bekerja',
            'urutan' => 3,
            'is_terminal' => false
        ]);

        $blockD = SurveyBlock::create([
            'survey_id' => $survey->id,
            'kode' => 'D',
            'nama' => 'Evaluasi Program Studi',
            'deskripsi' => 'Penilaian terhadap program studi yang diambil',
            'urutan' => 4,
            'is_terminal' => false
        ]);

        $blockE = SurveyBlock::create([
            'survey_id' => $survey->id,
            'kode' => 'E',
            'nama' => 'Penutup',
            'deskripsi' => 'Terima kasih dan penutup',
            'urutan' => 5,
            'is_terminal' => true
        ]);

        // Create questions for Block A (Demografis)
        $questionNama = TemplatePertanyaan::create([
            'id_survey' => $survey->id,
            'block_id' => $blockA->id,
            'pertanyaan' => 'Nama Lengkap',
            'deskripsi_pertanyaan' => 'Silakan masukkan nama lengkap Anda',
            'tipe' => 'text',
            'urutan' => 1,
            'visualisasi' => null,
            'blok' => 'A' // Keep for backward compatibility
        ]);

        $questionUsia = TemplatePertanyaan::create([
            'id_survey' => $survey->id,
            'block_id' => $blockA->id,
            'pertanyaan' => 'Berapa usia Anda saat ini?',
            'deskripsi_pertanyaan' => 'Masukkan usia dalam tahun',
            'tipe' => 'number',
            'urutan' => 2,
            'visualisasi' => 'bar',
            'blok' => 'A'
        ]);

        // Key branching question - Status Kerja
        $questionStatus = TemplatePertanyaan::create([
            'id_survey' => $survey->id,
            'block_id' => $blockA->id,
            'pertanyaan' => 'Apa status pekerjaan Anda saat ini?',
            'deskripsi_pertanyaan' => 'Pilih salah satu status yang paling sesuai',
            'tipe' => 'radio',
            'urutan' => 3,
            'visualisasi' => 'pie',
            'blok' => 'A'
        ]);

        // Options for status kerja
        $optionBelumKerja = TemplateJawaban::create([
            'id_template_pertanyaan' => $questionStatus->id,
            'pilihan_jawaban' => 'Belum bekerja / Mencari kerja',
            'urutan' => 1
        ]);

        $optionSudahKerja = TemplateJawaban::create([
            'id_template_pertanyaan' => $questionStatus->id,
            'pilihan_jawaban' => 'Sudah bekerja full-time',
            'urutan' => 2
        ]);

        $optionPartTime = TemplateJawaban::create([
            'id_template_pertanyaan' => $questionStatus->id,
            'pilihan_jawaban' => 'Bekerja part-time',
            'urutan' => 3
        ]);

        $optionWirausaha = TemplateJawaban::create([
            'id_template_pertanyaan' => $questionStatus->id,
            'pilihan_jawaban' => 'Wirausaha',
            'urutan' => 4
        ]);

        // Create questions for Block B (Fresh Graduate)
        $questionCariKerja = TemplatePertanyaan::create([
            'id_survey' => $survey->id,
            'block_id' => $blockB->id,
            'pertanyaan' => 'Sudah berapa lama Anda mencari pekerjaan?',
            'deskripsi_pertanyaan' => 'Dalam satuan bulan',
            'tipe' => 'number',
            'urutan' => 1,
            'visualisasi' => 'bar',
            'blok' => 'B'
        ]);

        $questionKesulitan = TemplatePertanyaan::create([
            'id_survey' => $survey->id,
            'block_id' => $blockB->id,
            'pertanyaan' => 'Apa kesulitan utama dalam mencari pekerjaan?',
            'deskripsi_pertanyaan' => 'Pilih yang paling relevan',
            'tipe' => 'checkbox',
            'urutan' => 2,
            'visualisasi' => 'bar',
            'blok' => 'B'
        ]);

        // Options for kesulitan
        TemplateJawaban::create([
            'id_template_pertanyaan' => $questionKesulitan->id,
            'pilihan_jawaban' => 'Kurangnya pengalaman kerja',
            'urutan' => 1
        ]);

        TemplateJawaban::create([
            'id_template_pertanyaan' => $questionKesulitan->id,
            'pilihan_jawaban' => 'Persaingan yang ketat',
            'urutan' => 2
        ]);

        TemplateJawaban::create([
            'id_template_pertanyaan' => $questionKesulitan->id,
            'pilihan_jawaban' => 'Skill tidak sesuai kebutuhan industri',
            'urutan' => 3
        ]);

        TemplateJawaban::create([
            'id_template_pertanyaan' => $questionKesulitan->id,
            'pilihan_jawaban' => 'Lokasi kerja yang jauh',
            'urutan' => 4
        ]);

        // Create questions for Block C (Sudah Bekerja)
        $questionPosisi = TemplatePertanyaan::create([
            'id_survey' => $survey->id,
            'block_id' => $blockC->id,
            'pertanyaan' => 'Apa posisi/jabatan Anda saat ini?',
            'deskripsi_pertanyaan' => 'Sebutkan posisi atau jabatan lengkap',
            'tipe' => 'text',
            'urutan' => 1,
            'visualisasi' => null,
            'blok' => 'C'
        ]);

        $questionGaji = TemplatePertanyaan::create([
            'id_survey' => $survey->id,
            'block_id' => $blockC->id,
            'pertanyaan' => 'Berapa range gaji Anda per bulan?',
            'deskripsi_pertanyaan' => 'Pilih range yang sesuai',
            'tipe' => 'select',
            'urutan' => 2,
            'visualisasi' => 'pie',
            'blok' => 'C'
        ]);

        // Options for gaji
        TemplateJawaban::create([
            'id_template_pertanyaan' => $questionGaji->id,
            'pilihan_jawaban' => '< Rp 3.000.000',
            'urutan' => 1
        ]);

        TemplateJawaban::create([
            'id_template_pertanyaan' => $questionGaji->id,
            'pilihan_jawaban' => 'Rp 3.000.000 - Rp 5.000.000',
            'urutan' => 2
        ]);

        TemplateJawaban::create([
            'id_template_pertanyaan' => $questionGaji->id,
            'pilihan_jawaban' => 'Rp 5.000.000 - Rp 8.000.000',
            'urutan' => 3
        ]);

        TemplateJawaban::create([
            'id_template_pertanyaan' => $questionGaji->id,
            'pilihan_jawaban' => '> Rp 8.000.000',
            'urutan' => 4
        ]);

        // Create questions for Block D (Evaluasi Program Studi)
        $questionRelevant = TemplatePertanyaan::create([
            'id_survey' => $survey->id,
            'block_id' => $blockD->id,
            'pertanyaan' => 'Seberapa relevan materi kuliah dengan pekerjaan/situasi Anda saat ini?',
            'deskripsi_pertanyaan' => 'Skala 1-5 (1=Tidak Relevan, 5=Sangat Relevan)',
            'tipe' => 'radio',
            'urutan' => 1,
            'visualisasi' => 'bar',
            'blok' => 'D'
        ]);

        // Options for relevansi
        for ($i = 1; $i <= 5; $i++) {
            TemplateJawaban::create([
                'id_template_pertanyaan' => $questionRelevant->id,
                'pilihan_jawaban' => (string)$i,
                'urutan' => $i
            ]);
        }

        $questionSaran = TemplatePertanyaan::create([
            'id_survey' => $survey->id,
            'block_id' => $blockD->id,
            'pertanyaan' => 'Saran untuk perbaikan program studi',
            'deskripsi_pertanyaan' => 'Berikan saran konstruktif untuk pengembangan program studi',
            'tipe' => 'textarea',
            'urutan' => 2,
            'visualisasi' => null,
            'blok' => 'D'
        ]);

        // Create questions for Block E (Penutup)
        $questionTerimakasih = TemplatePertanyaan::create([
            'id_survey' => $survey->id,
            'block_id' => $blockE->id,
            'pertanyaan' => 'Terima kasih atas partisipasi Anda!',
            'deskripsi_pertanyaan' => 'Survey telah selesai. Data Anda akan membantu pengembangan program studi.',
            'tipe' => 'info',
            'urutan' => 1,
            'visualisasi' => null,
            'blok' => 'E'
        ]);

        // Create branching rules
        
        // Rule 1: If "Belum bekerja" -> go to Block B
        SurveyBranchRule::create([
            'survey_id' => $survey->id,
            'source_question_id' => $questionStatus->id,
            'answer_option_id' => $optionBelumKerja->id,
            'target_block_id' => $blockB->id,
            'priority' => 1
        ]);

        // Rule 2: If "Sudah bekerja full-time" -> go to Block C
        SurveyBranchRule::create([
            'survey_id' => $survey->id,
            'source_question_id' => $questionStatus->id,
            'answer_option_id' => $optionSudahKerja->id,
            'target_block_id' => $blockC->id,
            'priority' => 2
        ]);

        // Rule 3: If "Bekerja part-time" -> go to Block C
        SurveyBranchRule::create([
            'survey_id' => $survey->id,
            'source_question_id' => $questionStatus->id,
            'answer_option_id' => $optionPartTime->id,
            'target_block_id' => $blockC->id,
            'priority' => 3
        ]);

        // Rule 4: If "Wirausaha" -> go to Block C
        SurveyBranchRule::create([
            'survey_id' => $survey->id,
            'source_question_id' => $questionStatus->id,
            'answer_option_id' => $optionWirausaha->id,
            'target_block_id' => $blockC->id,
            'priority' => 4
        ]);

        // Rule 5: Age-based rule - if age > 30, skip evaluation and go straight to ending
        SurveyBranchRule::create([
            'survey_id' => $survey->id,
            'source_question_id' => $questionUsia->id,
            'operator' => 'gt',
            'value_json' => 30,
            'target_block_id' => $blockE->id,
            'priority' => 1
        ]);

        // Rule 6: If salary is high (> 8M), skip evaluation
        $optionHighSalary = TemplateJawaban::where('id_template_pertanyaan', $questionGaji->id)
                                         ->where('pilihan_jawaban', '> Rp 8.000.000')
                                         ->first();

        if ($optionHighSalary) {
            SurveyBranchRule::create([
                'survey_id' => $survey->id,
                'source_question_id' => $questionGaji->id,
                'answer_option_id' => $optionHighSalary->id,
                'target_block_id' => $blockE->id,
                'priority' => 1
            ]);
        }

        $this->command->info('Demo survey with blocks and branching rules created successfully!');
        $this->command->info('Survey ID: ' . $survey->id);
        $this->command->info('Survey Name: ' . $survey->nama);
        $this->command->line('');
        $this->command->info('Blocks created:');
        $this->command->line('- A: Informasi Demografis');
        $this->command->line('- B: Fresh Graduate Path');  
        $this->command->line('- C: Working Professional Path');
        $this->command->line('- D: Program Evaluation');
        $this->command->line('- E: Closing (Terminal)');
        $this->command->line('');
        $this->command->info('Branching Rules:');
        $this->command->line('- Status kerja determines path B or C');
        $this->command->line('- Age > 30 skips evaluation and goes to closing');
        $this->command->line('- High salary skips evaluation');
    }
}
