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
                'subject' => 'Undangan Mengisi Survei',
                'body' => 'Kepada Yth. {{name}},\n\nAnda diundang untuk berpartisipasi dalam survei kami. 
                Survei ini dilaksanakan pada tanggal {{date}}. Diharapkan untuk mengisi survei ini pada rentang waktu tersebut.
                Berikut akun tracer study kamu:
                Email: {{email}}
                Password: {{password}}

                Silahkan login akun sesuai email dan password yang tertera di atas atau dapat mengklik button di bawah ini.
                Atas perhatiannya, kami ucapkan terima kasih.' ,
                
            ],
            [
                'name' => 'survey_reminder',
                'subject' => 'Pengingat: Silakan Lengkapi Survei',
                'body' => 'Kepada Yth. {{name}},\n\n
                Kami ingin mengingatkan bahwa Anda belum mengisi survei {{nama}}.
                Berikut akun tracer study kamu:\n\n
                Email: {{email}}\n
                Password: {{password}}\n
                Atas perhatiannya, kami ucapkan terima kasih.',
            ],
            [
                'name' => 'survey_appreciation',
                'subject' => 'Terima Kasih atas Partisipasi Anda',
                'body' => 'Kepada Yth. {{name}},\n\n 
                Terima kasih kami sampaikan atas kesediaan dan waktu yang telah Anda luangkan untuk mengisi survei kami. 
                Partisipasi Anda sangat berarti bagi kelancaran survei ini.\n\n
                Atas perhatiannya, kami ucapkan terima kasih.',
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
