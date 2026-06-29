<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration: Membuat tabel 'gurus' untuk menyimpan data guru
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gurus', function (Blueprint $table) {
            // Kolom Primary Key (String, bisa NIP atau ID unik manual)
            $table->string('id_guru')->primary();
            
            // Nama Guru
            $table->string('nama');
            
            // Foreign Key ke tabel 'users' untuk akun login
            $table->unsignedBigInteger('id_user');
            
            // No Telepon (Opsional)
            $table->string('no_telp')->nullable();
            
            // Foreign Key ke tabel 'mata_pelajaran'
            $table->unsignedBigInteger('id_mata_pelajaran');
            
            $table->timestamps(); // create_at & updated_at

            // Definisi Relasi (Foreign Key Constraints)
            $table->foreign('id_mata_pelajaran')
                ->references('id_mata_pelajaran')
                ->on('mata_pelajaran');

            $table->foreign('id_user')
                ->references('id_user')
                ->on('users')
                ->onDelete('cascade'); // Jika user dihapus, data guru juga hilang
        });
    }

    public function down(): void
    {
        // Menghapus tabel jika migration di-rollback
        Schema::dropIfExists('gurus');
    }
};
