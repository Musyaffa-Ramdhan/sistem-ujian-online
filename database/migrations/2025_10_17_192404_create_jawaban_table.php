<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jawaban_siswa', function (Blueprint $table) {
            $table->bigIncrements('id_jawaban_siswa');
            $table->unsignedBigInteger('id_ujian');
            $table->unsignedBigInteger('id_siswa');
            $table->unsignedBigInteger('id_soal');
            $table->string('jawaban_siswa')->nullable();
            $table->timestamps();

            $table->foreign('id_ujian')
                ->references('id_ujian')
                ->on('ujians');

            $table->foreign('id_siswa')
                ->references('id_siswa')
                ->on('siswas');

            $table->foreign('id_soal')
                ->references('id_soal')
                ->on('soal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jawaban_siswa');
    }
};
