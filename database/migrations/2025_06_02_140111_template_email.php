<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('template_email', function (Blueprint $table) {
            $table->id();
            $table->string('type')->unique();
            $table->text('subject');
            $table->text('body');
            $table->timestamps();
        });

        // Insert default email templates
        DB::table('template_email')->insert([
            [
                'type' => 'survey_invitation',
                'subject' => 'Undangan Mengisi Survei - {{survey_name}}',
                'body' => 'Kepada Yth. {{name}},

Anda diundang untuk berpartisipasi dalam survei {{survey_name}}. 
Survei ini dilaksanakan dari tanggal {{start_date}} sampai {{end_date}}. 
Diharapkan untuk mengisi survei ini pada rentang waktu tersebut.

Berikut akun tracer study Anda:
Email: {{email}}
Password: {{password}}

Silahkan login sesuai email dan password yang tertera di atas.
Atas perhatiannya, kami ucapkan terima kasih.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'survey_reminder',
                'subject' => 'Pengingat: Silakan Lengkapi Survei {{survey_name}}',
                'body' => 'Kepada Yth. {{name}},

Kami ingin mengingatkan bahwa Anda belum mengisi survei {{survey_name}}.
Batas waktu pengisian survei adalah {{end_date}}.

Berikut akun tracer study Anda:
Email: {{email}}
Password: {{password}}

Atas perhatiannya, kami ucapkan terima kasih.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'survey_appreciation',
                'subject' => 'Terima Kasih atas Partisipasi Anda',
                'body' => 'Kepada Yth. {{name}},

Terima kasih kami sampaikan atas kesediaan dan waktu yang telah Anda luangkan untuk mengisi survei {{survey_name}}.
Partisipasi Anda sangat berarti bagi kelancaran survei ini.

Atas perhatiannya, kami ucapkan terima kasih.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_email');
    }
};