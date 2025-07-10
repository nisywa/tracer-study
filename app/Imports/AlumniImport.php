<?php

namespace App\Imports;

use App\Models\Alumni;
use App\Models\SurveyUser;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AlumniImport implements ToModel, WithHeadingRow
{
    protected $survey_id;

    public function __construct($survey_id = null)
    {
        $this->survey_id = $survey_id;
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // try {
        //     // Validate required fields
        //     if (empty($row['email']) || empty($row['nama']) || empty($row['nip'])) {
        //         Log::warning("Skipping row due to missing required fields", $row);
        //         return null;
        //     }

        //     // Validate email format
        //     if (!filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
        //         throw new Exception("Invalid email format for: {$row['email']}");
        //     }

        //     // Create the user
        //     $user = User::firstOrCreate(
        //         ['email' => $row['email']], // Check for duplicate email
        //         [
        //             'name' => $row['nama'],
        //             'password' => bcrypt(substr($row['nip'], 0, 5)), // Use first 5 digits of NIP as password
        //             'role' => 'alumni',
        //         ]
        //     );

        //     // Assign role if not already assigned
        //     if (!$user->hasRole('alumni')) {
        //         $user->assignRole('alumni');
        //     }
        try {
            // Add name cleaning function
            $cleanName = function($name) {
                // Split by first comma and take first part
                $nameParts = explode(',', $name, 2);
                return trim($nameParts[0]);
            };

            // Validate required fields
            if (empty($row['nama'])) {
                Log::warning("Skipping row due to missing required fields", $row);
                return null;
            }

            // Clean the name before saving
            $cleanedName = $cleanName($row['nama']);

            //mengeluarkan 8 angka NIP untuk menjadi variabel tanggal_lahir
            if($row['nip']){
                $row['tanggal_lahir'] = \Carbon\Carbon::createFromFormat('Ymd', substr($row['nip'], 0, 8))->format('Y-m-d');
            }

            // Process dates before creating/updating
            $tanggalLahir = null;
            if (!empty($row['tanggal_lahir'])) {
                // Check if it's an Excel serial date (numeric)
                if (is_numeric($row['tanggal_lahir'])) {
                    // Convert Excel serial date to Carbon date
                    $tanggalLahir = \Carbon\Carbon::createFromFormat('Y-m-d', '1900-01-01')
                        ->addDays($row['tanggal_lahir'] - 2)
                        ->format('Y-m-d');
                } else {
                    // Parse regular date format
                    $tanggalLahir = \Carbon\Carbon::parse($row['tanggal_lahir'])->format('Y-m-d');
                }
            }
            $tahunLulus = !empty($row['tahun_lulus']) ? $row['tahun_lulus'] : null;

            // Find existing alumni by name and date of birth
            $existingAlumni = null;
            if ($tanggalLahir) {
                $existingAlumni = Alumni::where('nama', 'LIKE', $cleanedName . '%')
                    ->Where('tanggal_lahir', $tanggalLahir)
                    ->first();
            }
            if ($existingAlumni) {

                // Update existing record with new data
                $existingAlumni->update([
                    'nip' => $row['nip'] ?? $existingAlumni->nip,
                    'email' => $row['email'] ?? $existingAlumni->email,
                    'jabatan' => $row['jabatan'] ?? $existingAlumni->jabatan,
                    'satuan_kerja' => $row['satuan_kerja'] ?? $existingAlumni->satuan_kerja,
                    'unit_kerja' => $row['unit_kerja'] ?? $existingAlumni->unit_kerja,
                    'no_hp' => $row['no_hp'] ?? $existingAlumni->no_hp,
                    'nip_kepala_bps' => $row['nip_kepala_bps'] ?? $existingAlumni->nip_kepala_bps,
                    'tahun_lulus' => $tahunLulus ?? $existingAlumni->tahun_lulus
                ]);

                // Update the associated user if email is provided
                if (!empty($row['email']) && $existingAlumni->user) {
                    $existingAlumni->user->update([
                        'email' => $row['email'],
                        'name' => $cleanedName
                    ]);
                }

                // Create survey_user entry if survey_id is set and doesn't exist
                if ($this->survey_id && $existingAlumni->user) {
                    SurveyUser::firstOrCreate(
                        [
                            'survey_id' => $this->survey_id,
                            'user_id' => $existingAlumni->user->id,
                        ]
                    );
                }

                Log::info("Updated existing alumni: " . $cleanedName . " with birth date: " . $tanggalLahir);
                return $existingAlumni;
            }

            // Create new record if no existing alumni found
            // Create the user
            $user = User::firstOrCreate(
                ['email' => $row['email']],
                [
                    'name' => $cleanedName,
                    'password' => bcrypt(substr($row['nip'], 0, 5)),
                    'role' => 'alumni',
                ]
            );

            // Assign role if not already assigned
            if (!$user->hasRole('alumni')) {
                $user->assignRole('alumni');
            }

            // Create the alumni record
            $alumni = Alumni::create([
                'user_id' => $user->id,
                'nama' => $cleanedName,
                'nip' => $row['nip'] ?? '',
                'email' => $row['email'] ?? '',
                'jabatan' => $row['jabatan'] ?? '',
                'satuan_kerja' => $row['satuan_kerja'] ?? '',
                'unit_kerja' => $row['unit_kerja'] ?? '',
                'no_hp' => $row['no_hp'] ?? '',
                'nip_kepala_bps' => $row['nip_kepala_bps'] ?? '',
                'tanggal_lahir' => $tanggalLahir,
                'tahun_lulus' => $tahunLulus,
            ]);

            // Create survey_user entry if survey_id is set
            if ($this->survey_id) {
                SurveyUser::firstOrCreate(
                    [
                        'survey_id' => $this->survey_id,
                        'user_id' => $user->id,
                    ]
                );
            }

            Log::info("Created new alumni: " . $cleanedName . " with birth date: " . $tanggalLahir);

            return $alumni;
        } catch (QueryException $e) {
            Log::error("Database error during import: " . $e->getMessage());
            throw new Exception("Error importing data: " . $e->getMessage());
        } catch (Exception $e) {
            Log::error("Import error: " . $e->getMessage());
            throw new Exception("Error importing data: " . $e->getMessage());
        }
    }
}
