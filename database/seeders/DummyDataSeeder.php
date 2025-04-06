<?php
// filepath: /Users/miftahulhidayati/php-docker-dev/web-src/tracer-study/database/seeders/DummyDataSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Survey;
use App\Models\TemplatePertanyaan;
use App\Models\TemplateJawaban;
use App\Models\SurveyUser;
use App\Models\SurveyUserJawaban;
use App\Models\Alumni;
use App\Models\Atasan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // seed admin role
        // $adminRole = Role::create([
        //     'name' => 'admin'
        // ]);
        //  seed user role
        // $alumniRole = Role::create([
        //     'name' => 'alumni'
        // ]);
        // $atasanRole = Role::create([
        //     'name' => 'atasan'
        // ]);
        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'remember_token' => Str::random(10),
            ]
        );
        // Assign admin role
        $admin->assignRole('admin');
        // Create Survey
        $survey = Survey::firstOrCreate(
            ['nama' => 'Tracer Study 2024'],
            [
                'deskripsi' => 'Survei pelacakan untuk alumni tahun kelulusan 2020-2023',
                'tanggal_mulai' => Carbon::now()->subDays(30),
                'tanggal_selesai' => Carbon::now()->addDays(60),
                'created_by' => $admin->id,
                'type_survei' => 'alumni',
            ]
        );

        // Create Alumni (50 alumni)
        $alumni = [];
        for ($i = 1; $i <= 50; $i++) {
            $gender = ['L', 'P'][rand(0, 1)];
            $year = rand(2020, 2023);
            $prodi = ['Informatika', 'Sistem Informasi', 'Teknik Komputer', 'DKV'][rand(0, 3)];

            // Create user first
            $user = User::firstOrCreate(
                ['email' => 'alumni' . $i . '@example.com'],
                [
                    'name' => 'Alumni ' . $i,
                    'password' => Hash::make('password'),
                    'role' => 'alumni',
                    'remember_token' => Str::random(10),
                ]
            );
            // Assign alumni role
            $user->assignRole('alumni');

            // Create alumni record
            $alumni[] = Alumni::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'nama' => 'Alumni ' . $i,
                    'nip' => '1990' . str_pad($i, 6, '0', STR_PAD_LEFT),
                    'email' => 'alumni' . $i . '@example.com',
                    'jabatan' => ['Staff', 'Supervisor', 'Manager'][rand(0, 2)],
                    'satuan_kerja' => 'Satker ' . ceil($i / 5),
                    'unit_kerja' => 'Unit ' . ceil($i / 3),
                    'no_hp' => '08' . rand(1000000000, 9999999999),
                    'kepala_bps' => rand(0, 1) ? 'Ya' : 'Tidak',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
        }

        // Create Atasan (20 atasan)
        $atasans = [];
        for ($i = 1; $i <= 20; $i++) {
            // Create user first
            $user = User::firstOrCreate(
                ['email' => 'atasan' . $i . '@company.com'],
                [
                    'name' => 'Atasan ' . $i,
                    'password' => Hash::make('password'),
                    'role' => 'atasan',
                    'remember_token' => Str::random(10),
                ]
            );
            // Assign atasan role
            $user->assignRole('atasan');

            // Create atasan record
            $atasans[] = Atasan::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'nama' => 'Atasan ' . $i,
                    'nip' => '1980' . str_pad($i, 6, '0', STR_PAD_LEFT),
                    'email' => 'atasan' . $i . '@company.com',
                    'jabatan' => ['Manager', 'Supervisor', 'Director', 'Team Lead'][rand(0, 3)],
                    'satuan_kerja' => 'Satker ' . ceil($i / 2),
                    'unit_kerja' => 'Unit ' . ceil($i / 2),
                    'no_hp' => '08' . rand(1000000000, 9999999999),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
        }

        // Create Template Questions
        $questions = [
            [
                'pertanyaan' => 'Berapa lama waktu yang Anda butuhkan untuk mendapatkan pekerjaan pertama?',
                'tipe' => 'radio',
                'blok' => 'Riwayat Pekerjaan',
                'visualisasi' => 'pie',
                'options' => ['< 3 bulan', '3-6 bulan', '6-12 bulan', '> 12 bulan', 'Belum bekerja']
            ],
            [
                'pertanyaan' => 'Berapa gaji pertama Anda?',
                'tipe' => 'radio',
                'blok' => 'Riwayat Pekerjaan',
                'visualisasi' => 'bar',
                'options' => ['< 3 juta', '3-5 juta', '5-10 juta', '> 10 juta']
            ],
            [
                'pertanyaan' => 'Apakah pekerjaan Anda sesuai dengan bidang studi?',
                'tipe' => 'radio',
                'blok' => 'Kesesuaian Pekerjaan',
                'visualisasi' => 'pie',
                'options' => ['Sangat sesuai', 'Sesuai', 'Cukup sesuai', 'Kurang sesuai', 'Tidak sesuai']
            ],
            [
                'pertanyaan' => 'Bagaimana tingkat kepuasan Anda terhadap kurikulum program studi?',
                'tipe' => 'radio',
                'blok' => 'Evaluasi Kurikulum',
                'visualisasi' => 'bar',
                'options' => ['Sangat puas', 'Puas', 'Cukup puas', 'Kurang puas', 'Tidak puas']
            ],
            [
                'pertanyaan' => 'Fasilitas apa yang perlu ditingkatkan di kampus?',
                'tipe' => 'checkbox',
                'blok' => 'Fasilitas',
                'visualisasi' => 'bar',
                'options' => ['Perpustakaan', 'Laboratorium', 'Ruang kuliah', 'Internet/WiFi', 'Kantin', 'Fasilitas olahraga']
            ],
            [
                'pertanyaan' => 'Apakah Anda melanjutkan pendidikan setelah lulus?',
                'tipe' => 'radio',
                'blok' => 'Pendidikan Lanjutan',
                'visualisasi' => 'pie',
                'options' => ['Ya, S2', 'Ya, S3', 'Tidak', 'Berencana']
            ],
            [
                'pertanyaan' => 'Keahlian apa yang menurut Anda kurang diajarkan di kampus?',
                'tipe' => 'checkbox',
                'blok' => 'Keahlian',
                'visualisasi' => 'bar',
                'options' => ['Programming', 'Public Speaking', 'Bahasa Asing', 'Leadership', 'Research', 'Entrepreneurship']
            ],
            [
                'pertanyaan' => 'Berapa jumlah tempat Anda bekerja sejak lulus?',
                'tipe' => 'radio',
                'blok' => 'Riwayat Pekerjaan',
                'visualisasi' => 'bar',
                'options' => ['1 perusahaan', '2 perusahaan', '3 perusahaan', '> 3 perusahaan', 'Belum bekerja']
            ]
        ];

        $questionIds = [];

        // Create template questions and answers
        foreach ($questions as $index => $questionData) {
            $question = TemplatePertanyaan::firstOrCreate(
                ['pertanyaan' => $questionData['pertanyaan'], 'id_survey' => $survey->id],
                [
                    'id_survey' => $survey->id,
                    'tipe' => $questionData['tipe'],
                    'urutan' => $index + 1,
                    'blok' => $questionData['blok'],
                    'deskripsi_pertanyaan' => 'Deskripsi untuk ' . $questionData['pertanyaan'],
                    'visualisasi' => $questionData['visualisasi'],
                ]
            );

            $questionIds[] = $question->id;

            // Create options for this question
            foreach ($questionData['options'] as $optionIndex => $option) {
                TemplateJawaban::firstOrCreate(
                    ['id_template_pertanyaan' => $question->id, 'pilihan_jawaban' => $option],
                    [
                        'urutan' => $optionIndex + 1,
                    ]
                );
            }
        }

        // Create Survey Users - Assign each alumni to the survey
        foreach ($alumni as $alumnus) {
            $surveyUser = SurveyUser::firstOrCreate(
                ['survey_id' => $survey->id, 'user_id' => $alumnus->user_id],
                [
                    'status' => rand(0, 1),
                    'tanggal_mengisi' => Carbon::now()->subDays(rand(0, 29)),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );

            // Generate answers only for completed surveys
            if ($surveyUser->status == 1) {
                // Generate answers for this user
                foreach ($questionIds as $questionId) {
                    $question = TemplatePertanyaan::find($questionId);
                    $options = TemplateJawaban::where('id_template_pertanyaan', $questionId)->pluck('id')->toArray();

                    // For radio questions, select one option
                    if ($question->tipe === 'radio') {
                        $selectedOption = $options[array_rand($options)];

                        $option = TemplateJawaban::find($selectedOption);
                        SurveyUserJawaban::firstOrCreate(
                            [
                                'survey_user_id' => $surveyUser->id,
                                'template_pertanyaan_id' => $questionId,
                                'jawaban' => $option->pilihan_jawaban,
                            ]
                        );
                    }
                    // For checkbox questions, select multiple options (1 to 3)
                    else if ($question->tipe === 'checkbox') {
                        $numOptions = min(count($options), rand(1, 3));
                        $selectedOptions = array_rand($options, $numOptions);

                        if (!is_array($selectedOptions)) {
                            $selectedOptions = [$selectedOptions];
                        }

                        foreach ($selectedOptions as $optionIndex) {
                            $option = TemplateJawaban::find($options[$optionIndex]);
                            SurveyUserJawaban::firstOrCreate(
                                [
                                    'survey_user_id' => $surveyUser->id,
                                    'template_pertanyaan_id' => $questionId,
                                    'jawaban' => $option->pilihan_jawaban,
                                ]
                            );
                        }
                    }
                }
            }
        }

        // Debug data checks
        $this->command->info('Checking created data...');

        // Check survey and its questions
        $survey = Survey::first();
        $this->command->info("Survey created: " . ($survey ? 'Yes' : 'No'));
        if ($survey) {
            $this->command->info("Survey name: {$survey->nama}");
            $this->command->info("Questions created: " . TemplatePertanyaan::where('id_survey', $survey->id)->count());
            $this->command->info("Questions with visualization: " .
                TemplatePertanyaan::where('id_survey', $survey->id)
                ->whereNotNull('visualisasi')
                ->count());
        }

        // Check response counts
        $totalSurveyUsers = SurveyUser::count();
        $completedSurveys = SurveyUser::where('status', true)->count();
        $this->command->info("Total survey users: {$totalSurveyUsers}");
        $this->command->info("Completed surveys: {$completedSurveys}");

        // Check a sample question and its answers
        $sampleQuestion = TemplatePertanyaan::whereNotNull('visualisasi')->first();
        if ($sampleQuestion) {
            $this->command->info("\nSample question details:");
            $this->command->info("Question: {$sampleQuestion->pertanyaan}");
            $this->command->info("Type: {$sampleQuestion->tipe}");
            $this->command->info("Visualization: {$sampleQuestion->visualisasi}");

            $answerCount = SurveyUserJawaban::where('template_pertanyaan_id', $sampleQuestion->id)->count();
            $this->command->info("Total answers: {$answerCount}");

            if ($answerCount > 0) {
                $answers = SurveyUserJawaban::where('template_pertanyaan_id', $sampleQuestion->id)
                    ->take(5)
                    ->get();
                $this->command->info("Sample answers: " . $answers->pluck('jawaban')->join(', '));
            }
        }

        $this->command->info("\nData creation and verification completed!");
    }
}
