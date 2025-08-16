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
        Schema::table('survey_user', function (Blueprint $table) {
            $table->foreignId('current_question_id')->nullable()->after('status')->constrained('template_pertanyaan')->onDelete('set null');
            $table->string('guest_token')->nullable()->after('current_question_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_user', function (Blueprint $table) {
            $table->dropForeign(['current_question_id']);
            $table->dropColumn(['current_question_id', 'guest_token']);
        });
    }
};
