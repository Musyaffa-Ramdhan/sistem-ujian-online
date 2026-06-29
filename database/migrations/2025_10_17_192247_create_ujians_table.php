<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration: Membuat tabel 'ujians' untuk menampung data ujian
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ujians', function (Blueprint $table) {
            $table->bigIncrements('id_ujian');
            
            // Judul Ujian
            $table->string('nama_ujian');
            
            // Foreign key ke guru pembuat
            $table->string('id_guru');
            
            // Mata pelajaran yang diujikan
            $table->unsignedBigInteger('id_mata_pelajaran');
            
            // Target kelas yang harus mengerjakan
            $table->unsignedBigInteger('id_kelas');
            
            // Jadwal pelaksanaan
            $table->date('tanggal_ujian');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            
            // Durasi pengerjaan (dalam menit)
            $table->integer('durasi');
            
            $table->timestamps();

            // Definisi Relasi
            $table->foreign('id_guru')
                ->references('id_guru')
                ->on('gurus')
                ->onDelete('cascade');

            $table->foreign('id_mata_pelajaran')
                ->references('id_mata_pelajaran')
                ->on('mata_pelajaran');

            $table->foreign('id_kelas')
                ->references('id_kelas')
                ->on('kelas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ujians');
    }
};
