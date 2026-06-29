<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_ujian', function (Blueprint $table) {
            $table->bigIncrements('id_laporan_ujian');
            $table->unsignedBigInteger('kode_hasil_ujian');
            $table->text('laporan');
            $table->timestamps();

            $table->foreign('kode_hasil_ujian')
                ->references('kode_hasil_ujian')
                ->on('hasil_ujians')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_ujian');
    }
};
