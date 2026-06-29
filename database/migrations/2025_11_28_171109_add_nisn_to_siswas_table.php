<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration: Menambahkan kolom NISN ke tabel 'siswas' yang sudah ada
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            // Menambahkan kolom nisn (10 karakter, unik, diletakkan setelah kolom nama)
            $table->string('nisn', 10)->unique()->after('nama');
        });
    }

    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            // Menghapus kolom nisn jika rollback
            $table->dropColumn('nisn');
        });
    }
};
