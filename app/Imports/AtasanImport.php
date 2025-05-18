<?php

namespace App\Imports;

use App\Models\Atasan;
use App\Models\SurveyUser;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AtasanImport implements ToModel, WithHeadingRow
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
        try{
            // Validate required fields
            if (empty($row['email']) || empty($row['nama']) || empty($row['nip'])) {
                Log::warning("Skipping row due to missing required fields", $row);
                return null;
            }

            // Validate email format
            if (!filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email format for: {$row['email']}");
            }

            // Create the user
            $user = User::updateOrCreate(
            ['email' => $row['email']], // Check for duplicate email
            [
                'name' => $row['nama'],
                'password' => bcrypt(substr($row['nip'], 0, 5)), // Use first 5 digits of NIP as password
                'role' => 'atasan',
            ]
        );

        // Assign role if not already assigned
        if (!$user->hasRole('atasan')) {
            $user->assignRole('atasan');
        }

        // Create the atasan record
        $atasan = Atasan::firstOrCreate(
            ['nip' => $row['nip']],
            [
                'user_id' => $user->id,
                'nama' => $row['nama'],
                'nip' => $row['nip'],
                'email' => $row['email'],
                'jabatan' => $row['jabatan'] ?? '',
                'satuan_kerja' => $row['satuan_kerja'] ?? '',
                'unit_kerja' => $row['unit_kerja'] ?? '',
                'no_hp' => $row['no_hp'] ?? '',
            ]
        );

        // Create survey_user entry if survey_id is set
        if ($this->survey_id) {
            SurveyUser::firstOrCreate(
                [
                    'survey_id' => $this->survey_id,
                    'user_id' => $user->id,
                ]
            );
        }
        return $atasan;
    } catch (QueryException $e) {
        Log::error("Database error during import: " . $e->getMessage());
            throw new Exception("Error importing data: " . $e->getMessage());
        } catch (Exception $e) {
            Log::error("Import error: " . $e->getMessage());
            throw new Exception("Error importing data: " . $e->getMessage());
  }
    }
}