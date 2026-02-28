<?php

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
use App\Models\SurveyBlock;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;

class DummyDataSeeder2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@stis.ac.id'],
            [
                'name' => 'Administrator STIS',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'remember_token' => Str::random(10),
            ]
        );
        $admin->assignRole('admin');

        // Create Supervisor User
        $supervisor = User::firstOrCreate(
            ['email' => 'supervisor@stis.ac.id'],
            [
                'name' => 'Supervisor STIS',
                'password' => Hash::make('password'),
                'role' => 'supervisor',
                'remember_token' => Str::random(10),
            ]
        );
        $supervisor->assignRole('supervisor');

        // Create Survey Alumni
        $surveyAlumni = Survey::firstOrCreate(
            ['nama' => 'Tracer Study Alumni STIS 2024'],
            [
                'deskripsi' => 'Survei pelacakan alumni Politeknik Statistika STIS untuk mengetahui kondisi kerja dan feedback terhadap pendidikan',
                'tanggal_mulai' => Carbon::now()->subDays(60),
                'tanggal_selesai' => Carbon::now()->addDays(90),
                'created_by' => $admin->id,
                'type_survei' => 'alumni',
            ]
        );

        // Create Survey Atasan
        $surveyAtasan = Survey::firstOrCreate(
            ['nama' => 'Survey Atasan Alumni STIS 2024'],
            [
                'deskripsi' => 'Survei penilaian atasan terhadap kinerja alumni Politeknik Statistika STIS di tempat kerja',
                'tanggal_mulai' => Carbon::now()->subDays(60),
                'tanggal_selesai' => Carbon::now()->addDays(90),
                'created_by' => $admin->id,
                'type_survei' => 'atasan',
            ]
        );

        // Create Alumni Data (60 alumni)
        $alumni = [];
        $satuanKerjaList = [
            'BPS Pusat',
            'BPS Provinsi DKI Jakarta',
            'BPS Provinsi Jawa Barat',
            'BPS Provinsi Jawa Tengah',
            'BPS Provinsi Jawa Timur',
            'BPS Provinsi Sumatra Utara',
            'BPS Provinsi Bali',
            'BPS Kab. Bogor',
            'BPS Kab. Bandung',
            'BPS Kab. Semarang',
            'BPS Kab. Surabaya',
            'BPS Kab. Medan',
            'Kementerian Keuangan RI',
            'Bank Indonesia',
            'OJK (Otoritas Jasa Keuangan)',
            'Kemenperin',
        ];

        $unitKerjaList = [
            'Bagian Statistik Sosial',
            'Bagian Statistik Ekonomi',
            'Bagian Statistik Produksi',
            'Bagian Integrasi Pengolahan Data',
            'Bagian Neraca Wilayah Regional',
            'Bagian Statistik Distribusi',
            'Subbag Umum',
            'Bidang IPDS',
            'Bidang Statistik Sosial',
            'Bidang Statistik Ekonomi',
            'Seksi Statistik Produksi',
            'Seksi Statistik Distribusi',
            'Seksi Neraca Wilayah',
            'Seksi IPDS',
            'Unit Pelayanan Statistik Terpadu',
        ];

        $jabatanList = [
            'Statistisi Ahli Pertama',
            'Statistisi Ahli Muda',
            'Statistisi Ahli Madya',
            'Statistisi Penyelia',
            'Statistisi Mahir',
            'Statistisi Terampil',
            'Analis Data',
            'Pranata Komputer Ahli Pertama',
            'Pranata Komputer Ahli Muda',
            'Kepala Seksi',
            'Kepala Subbagian',
            'Staff Ahli',
        ];

        for ($i = 1; $i <= 60; $i++) {
            $tahunLulus = rand(2019, 2023);
            $nim = '2' . substr($tahunLulus, 2) . '1' . str_pad($i, 4, '0', STR_PAD_LEFT);
            
            // Create user first
            $user = User::firstOrCreate(
                ['email' => 'alumni' . $i . '@stis.ac.id'],
                [
                    'name' => 'Alumni ' . $i . ' STIS',
                    'password' => Hash::make('password'),
                    'role' => 'alumni',
                    'remember_token' => Str::random(10),
                ]
            );
            $user->assignRole('alumni');

            // Create alumni record with current attributes
            $alumni[] = Alumni::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'nama' => $user->name,
                    'nip' => '199' . rand(0, 9) . str_pad($i, 8, '0', STR_PAD_LEFT),
                    'email' => $user->email,
                    'jabatan' => $jabatanList[array_rand($jabatanList)],
                    'satuan_kerja' => $satuanKerjaList[array_rand($satuanKerjaList)],
                    'unit_kerja' => $unitKerjaList[array_rand($unitKerjaList)],
                    'no_hp' => '08' . rand(1000000000, 9999999999),
                    'nip_kepala_bps' => '196' . rand(0, 9) . str_pad(rand(1, 999), 8, '0', STR_PAD_LEFT),
                    'tanggal_lahir' => Carbon::create(rand(1990, 2000), rand(1, 12), rand(1, 28))->format('Y-m-d'),
                    'tahun_lulus' => $tahunLulus,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
        }

        // Create Atasan Data (30 atasan)
        $atasans = [];
        for ($i = 1; $i <= 30; $i++) {
            // Create user first
            $user = User::firstOrCreate(
                ['email' => 'atasan' . $i . '@bps.go.id'],
                [
                    'name' => 'Atasan ' . $i,
                    'password' => Hash::make('password'),
                    'role' => 'atasan',
                    'remember_token' => Str::random(10),
                ]
            );
            $user->assignRole('atasan');

            // Create atasan record with current attributes
            $atasans[] = Atasan::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'nama' => $user->name,
                    'nip' => '196' . rand(0, 9) . str_pad($i, 8, '0', STR_PAD_LEFT),
                    'email' => $user->email,
                    'jabatan' => ['Kepala Bidang', 'Kepala Seksi', 'Kepala Subbagian', 'Kabid IPDS', 'Kabid Sosial', 'Kabid Produksi', 'Kabid Distribusi', 'Kapus BPS'][array_rand(['Kepala Bidang', 'Kepala Seksi', 'Kepala Subbagian', 'Kabid IPDS', 'Kabid Sosial', 'Kabid Produksi', 'Kabid Distribusi', 'Kapus BPS'])],
                    'satuan_kerja' => $satuanKerjaList[array_rand($satuanKerjaList)],
                    'unit_kerja' => $unitKerjaList[array_rand($unitKerjaList)],
                    'no_hp' => '08' . rand(1000000000, 9999999999),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
        }

        // Create Survey Blocks for Alumni Survey
        $alumniBlocks = [
            ['kode' => 'A', 'nama' => 'Data Diri', 'deskripsi' => 'Informasi personal alumni'],
            ['kode' => 'B', 'nama' => 'Riwayat Pendidikan', 'deskripsi' => 'Informasi pendidikan lanjutan'],
            ['kode' => 'C', 'nama' => 'Riwayat Pekerjaan', 'deskripsi' => 'Informasi karir dan pekerjaan'],
            ['kode' => 'D', 'nama' => 'Kompetensi', 'deskripsi' => 'Penilaian kompetensi yang dimiliki'],
            ['kode' => 'E', 'nama' => 'Evaluasi Pendidikan', 'deskripsi' => 'Feedback terhadap pendidikan di STIS'],
        ];

        $blockIds = [];
        foreach ($alumniBlocks as $index => $blockData) {
            $block = SurveyBlock::firstOrCreate(
                ['survey_id' => $surveyAlumni->id, 'kode' => $blockData['kode']],
                [
                    'nama' => $blockData['nama'],
                    'deskripsi' => $blockData['deskripsi'],
                    'urutan' => $index + 1,
                    'is_terminal' => false,
                ]
            );
            $blockIds[$blockData['kode']] = $block->id;
        }

        // Create Template Questions for Alumni Survey
        $alumniQuestions = [
            // Block A - Data Diri
            [
                'pertanyaan' => 'Dalam berapa bulan Anda mendapatkan pekerjaan setelah lulus?',
                'tipe' => 'radio',
                'blok' => 'A',
                'visualisasi' => 'pie',
                'options' => ['Kurang dari 3 bulan', '3-6 bulan', '6-12 bulan', 'Lebih dari 12 bulan', 'Belum bekerja'],
                'is_required' => true,
            ],
            // Block B - Riwayat Pendidikan
            [
                'pertanyaan' => 'Apakah Anda melanjutkan pendidikan setelah lulus dari STIS?',
                'tipe' => 'radio',
                'blok' => 'B',
                'visualisasi' => 'pie',
                'options' => ['Ya, sedang kuliah S1', 'Ya, sudah lulus S1', 'Ya, sedang kuliah S2', 'Ya, sudah lulus S2', 'Tidak'],
                'is_required' => true,
            ],
            [
                'pertanyaan' => 'Jika melanjutkan pendidikan, bidang apa yang Anda ambil?',
                'tipe' => 'radio',
                'blok' => 'B',
                'visualisasi' => 'bar',
                'options' => ['Statistika', 'Matematika', 'Ekonomi', 'Komputer/IT', 'Manajemen', 'Akuntansi', 'Lainnya'],
                'is_required' => false,
            ],
            // Block C - Riwayat Pekerjaan
            [
                'pertanyaan' => 'Sektor apa tempat Anda bekerja saat ini?',
                'tipe' => 'radio',
                'blok' => 'C',
                'visualisasi' => 'bar',
                'options' => ['Pemerintah Pusat', 'Pemerintah Daerah', 'BUMN', 'BUMD', 'Swasta Nasional', 'Swasta Multinasional', 'Wiraswasta', 'Lainnya'],
                'is_required' => true,
            ],
            [
                'pertanyaan' => 'Berapa range gaji Anda saat ini?',
                'tipe' => 'radio',
                'blok' => 'C',
                'visualisasi' => 'bar',
                'options' => ['< 3 juta', '3-5 juta', '5-8 juta', '8-12 juta', '12-20 juta', '> 20 juta'],
                'is_required' => true,
            ],
            [
                'pertanyaan' => 'Seberapa erat hubungan pekerjaan Anda dengan bidang statistika?',
                'tipe' => 'radio',
                'blok' => 'C',
                'visualisasi' => 'pie',
                'options' => ['Sangat erat', 'Erat', 'Cukup erat', 'Kurang erat', 'Tidak ada hubungan'],
                'is_required' => true,
            ],
            // Block D - Kompetensi
            [
                'pertanyaan' => 'Seberapa baik kemampuan statistika Anda saat ini?',
                'tipe' => 'radio',
                'blok' => 'D',
                'visualisasi' => 'bar',
                'options' => ['Sangat baik', 'Baik', 'Cukup baik', 'Kurang baik', 'Sangat kurang'],
                'is_required' => true,
            ],
            [
                'pertanyaan' => 'Seberapa baik kemampuan teknologi informasi Anda?',
                'tipe' => 'radio',
                'blok' => 'D',
                'visualisasi' => 'bar',
                'options' => ['Sangat baik', 'Baik', 'Cukup baik', 'Kurang baik', 'Sangat kurang'],
                'is_required' => true,
            ],
            [
                'pertanyaan' => 'Kemampuan apa yang masih perlu ditingkatkan?',
                'tipe' => 'checkbox',
                'blok' => 'D',
                'visualisasi' => 'bar',
                'options' => ['Bahasa Inggris', 'Komunikasi', 'Leadership', 'Public Speaking', 'Analisis Data', 'Programming', 'Manajemen Proyek'],
                'is_required' => false,
            ],
            // Block E - Evaluasi Pendidikan
            [
                'pertanyaan' => 'Bagaimana penilaian Anda terhadap kurikulum STIS?',
                'tipe' => 'radio',
                'blok' => 'E',
                'visualisasi' => 'bar',
                'options' => ['Sangat baik', 'Baik', 'Cukup baik', 'Kurang baik', 'Sangat kurang'],
                'is_required' => true,
            ],
            [
                'pertanyaan' => 'Apa yang perlu ditingkatkan dari pendidikan di STIS?',
                'tipe' => 'checkbox',
                'blok' => 'E',
                'visualisasi' => 'bar',
                'options' => ['Fasilitas laboratorium', 'Metode pengajaran', 'Kurikulum', 'Soft skills', 'Praktik kerja', 'Penelitian', 'Kerjasama industri'],
                'is_required' => false,
            ],
            [
                'pertanyaan' => 'Secara keseluruhan, bagaimana kepuasan Anda terhadap pendidikan di STIS?',
                'tipe' => 'radio',
                'blok' => 'E',
                'visualisasi' => 'pie',
                'options' => ['Sangat puas', 'Puas', 'Cukup puas', 'Kurang puas', 'Sangat tidak puas'],
                'is_required' => true,
            ],
        ];

        $alumniQuestionIds = [];

        // Create template questions and answers for Alumni Survey
        foreach ($alumniQuestions as $index => $questionData) {
            $question = TemplatePertanyaan::firstOrCreate(
                ['pertanyaan' => $questionData['pertanyaan'], 'id_survey' => $surveyAlumni->id],
                [
                    'id_survey' => $surveyAlumni->id,
                    'block_id' => $blockIds[$questionData['blok']],
                    'tipe' => $questionData['tipe'],
                    'urutan' => $index + 1,
                    'deskripsi_pertanyaan' => 'Deskripsi untuk ' . $questionData['pertanyaan'],
                    'visualisasi' => $questionData['visualisasi'],
                    'is_required' => $questionData['is_required'],
                ]
            );

            $alumniQuestionIds[] = $question->id;

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

        // Create Survey Blocks for Atasan Survey
        $atasanBlocks = [
            ['kode' => 'A', 'nama' => 'Informasi Alumni', 'deskripsi' => 'Data alumni yang dinilai'],
            ['kode' => 'B', 'nama' => 'Penilaian Kompetensi', 'deskripsi' => 'Penilaian kemampuan alumni'],
            ['kode' => 'C', 'nama' => 'Evaluasi Kinerja', 'deskripsi' => 'Penilaian kinerja alumni'],
        ];

        $atasanBlockIds = [];
        foreach ($atasanBlocks as $index => $blockData) {
            $block = SurveyBlock::firstOrCreate(
                ['survey_id' => $surveyAtasan->id, 'kode' => $blockData['kode']],
                [
                    'nama' => $blockData['nama'],
                    'deskripsi' => $blockData['deskripsi'],
                    'urutan' => $index + 1,
                    'is_terminal' => false,
                ]
            );
            $atasanBlockIds[$blockData['kode']] = $block->id;
        }

        // Create Template Questions for Atasan Survey
        $atasanQuestions = [
            [
                'pertanyaan' => 'Bagaimana penilaian Anda terhadap kemampuan teknis alumni dalam bidang statistika?',
                'tipe' => 'radio',
                'blok' => 'B',
                'visualisasi' => 'bar',
                'options' => ['Sangat baik', 'Baik', 'Cukup baik', 'Kurang baik', 'Sangat kurang'],
                'is_required' => true,
            ],
            [
                'pertanyaan' => 'Bagaimana kemampuan komunikasi alumni?',
                'tipe' => 'radio',
                'blok' => 'B',
                'visualisasi' => 'bar',
                'options' => ['Sangat baik', 'Baik', 'Cukup baik', 'Kurang baik', 'Sangat kurang'],
                'is_required' => true,
            ],
            [
                'pertanyaan' => 'Bagaimana kemampuan bekerja dalam tim alumni?',
                'tipe' => 'radio',
                'blok' => 'B',
                'visualisasi' => 'bar',
                'options' => ['Sangat baik', 'Baik', 'Cukup baik', 'Kurang baik', 'Sangat kurang'],
                'is_required' => true,
            ],
            [
                'pertanyaan' => 'Bagaimana kemampuan problem solving alumni?',
                'tipe' => 'radio',
                'blok' => 'B',
                'visualisasi' => 'bar',
                'options' => ['Sangat baik', 'Baik', 'Cukup baik', 'Kurang baik', 'Sangat kurang'],
                'is_required' => true,
            ],
            [
                'pertanyaan' => 'Secara keseluruhan, bagaimana penilaian Anda terhadap kinerja alumni?',
                'tipe' => 'radio',
                'blok' => 'C',
                'visualisasi' => 'pie',
                'options' => ['Sangat puas', 'Puas', 'Cukup puas', 'Kurang puas', 'Sangat tidak puas'],
                'is_required' => true,
            ],
            [
                'pertanyaan' => 'Apakah Anda akan merekomendasikan lulusan STIS untuk bekerja di instansi Anda?',
                'tipe' => 'radio',
                'blok' => 'C',
                'visualisasi' => 'pie',
                'options' => ['Sangat merekomendasikan', 'Merekomendasikan', 'Cukup merekomendasikan', 'Kurang merekomendasikan', 'Tidak merekomendasikan'],
                'is_required' => true,
            ],
        ];

        $atasanQuestionIds = [];

        // Create template questions and answers for Atasan Survey
        foreach ($atasanQuestions as $index => $questionData) {
            $question = TemplatePertanyaan::firstOrCreate(
                ['pertanyaan' => $questionData['pertanyaan'], 'id_survey' => $surveyAtasan->id],
                [
                    'id_survey' => $surveyAtasan->id,
                    'block_id' => $atasanBlockIds[$questionData['blok']],
                    'tipe' => $questionData['tipe'],
                    'urutan' => $index + 1,
                    'deskripsi_pertanyaan' => 'Deskripsi untuk ' . $questionData['pertanyaan'],
                    'visualisasi' => $questionData['visualisasi'],
                    'is_required' => $questionData['is_required'],
                ]
            );

            $atasanQuestionIds[] = $question->id;

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

        // Create Survey Users and Answers for Alumni
        foreach ($alumni as $alumnus) {
            $surveyUser = SurveyUser::firstOrCreate(
                ['survey_id' => $surveyAlumni->id, 'user_id' => $alumnus->user_id],
                [
                    'status' => rand(0, 1) ? 1 : rand(0, 1), // 75% completion rate
                    'tanggal_mengisi' => rand(0, 1) ? Carbon::now()->subDays(rand(0, 59)) : null,
                    'created_at' => Carbon::now()->subDays(rand(0, 59)),
                    'updated_at' => Carbon::now(),
                ]
            );

            // Generate answers only for completed surveys
            if ($surveyUser->status == 1 && $surveyUser->tanggal_mengisi) {
                foreach ($alumniQuestionIds as $questionId) {
                    $question = TemplatePertanyaan::find($questionId);
                    $options = TemplateJawaban::where('id_template_pertanyaan', $questionId)->pluck('id')->toArray();

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
                    } else if ($question->tipe === 'checkbox') {
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

        // Create Survey Users and Answers for Atasan
        foreach ($atasans as $atasan) {
            $surveyUser = SurveyUser::firstOrCreate(
                ['survey_id' => $surveyAtasan->id, 'user_id' => $atasan->user_id],
                [
                    'status' => rand(0, 1) ? 1 : rand(0, 1), // 75% completion rate
                    'tanggal_mengisi' => rand(0, 1) ? Carbon::now()->subDays(rand(0, 59)) : null,
                    'created_at' => Carbon::now()->subDays(rand(0, 59)),
                    'updated_at' => Carbon::now(),
                ]
            );

            // Generate answers only for completed surveys
            if ($surveyUser->status == 1 && $surveyUser->tanggal_mengisi) {
                foreach ($atasanQuestionIds as $questionId) {
                    $question = TemplatePertanyaan::find($questionId);
                    $options = TemplateJawaban::where('id_template_pertanyaan', $questionId)->pluck('id')->toArray();

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
                }
            }
        }

        // Output summary information
        $this->command->info('=== Data Seeding Completed ===');
        $this->command->info('Alumni created: ' . count($alumni));
        $this->command->info('Atasan created: ' . count($atasans));
        $this->command->info('Alumni surveys completed: ' . SurveyUser::where('survey_id', $surveyAlumni->id)->where('status', 1)->count());
        $this->command->info('Atasan surveys completed: ' . SurveyUser::where('survey_id', $surveyAtasan->id)->where('status', 1)->count());
        $this->command->info('Total survey responses: ' . SurveyUserJawaban::count());

        // Verify visualization data
        $questionsWithViz = TemplatePertanyaan::whereNotNull('visualisasi')->count();
        $this->command->info('Questions with visualization: ' . $questionsWithViz);

        $this->command->info('=== Ready for visualization! ===');
    }
}
