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
        try {
            // Create the user
            $user = User::firstOrCreate(
                ['email' => $row['email']], // Check for duplicate email
                [
                    'name' => $row['nama'],
                    'password' => bcrypt('default_password'), // Handle password securely
                ]
            );

            // Create the alumni record
            $alumni = Alumni::firstOrCreate(['nip' => $row['nip']], [
                'user_id' => $user->id,
                'nama' => $row['nama'],
                'nip' => $row['nip'],
                'email' => $row['email'],
                'jabatan' => $row['jabatan'],
                'satuan_kerja' => $row['satuan_kerja'],
                'unit_kerja' => $row['unit_kerja'],
                'no_hp' => $row['no_hp'],
                'kepala_bps' => $row['kepala_bps'],
                'nip_kepala_bps' => $row['nip_kepala_bps'],
            ]);

            // Create survey_user entry if survey_id is set
            if ($this->survey_id) {
                SurveyUser::create([
                    'survey_id' => $this->survey_id,
                    'user_id' => $user->id,
                ]);
            }
            return $alumni;
        } catch (QueryException $e) {
            // Log or handle the error
            Log::error("message: {$e->getMessage()}");
            throw $e;
        } catch (Exception $e) {


            // Log or handle the error
            Log::error("message: {$e->getMessage()}");
            throw $e;
        }
    }
}
