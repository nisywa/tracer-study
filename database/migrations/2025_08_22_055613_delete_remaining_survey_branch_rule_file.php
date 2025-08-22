<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hapus file SurveyBranchRule.php yang masih tersisa
        $fileToDelete = app_path('Models/SurveyBranchRule.php');
        
        if (File::exists($fileToDelete)) {
            File::delete($fileToDelete);
            echo "Deleted remaining file: " . $fileToDelete . "\n";
        } else {
            echo "File already deleted or not found: " . $fileToDelete . "\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak ada yang perlu di-rollback karena ini hanya menghapus file
        echo "No rollback needed - this migration only deletes files.\n";
    }
};
