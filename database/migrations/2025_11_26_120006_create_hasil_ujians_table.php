<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_ujians', function (Blueprint $table) {
            $table->bigIncrements('kode_hasil_ujian');
            $table->unsignedBigInteger('id_ujian');
            $table->unsignedBigInteger('id_siswa');
            $table->integer('total_benar')->default(0);
            $table->integer('total_salah')->default(0);
            $table->integer('total_tidak_dijawab')->default(0);
            $table->integer('nilai')->default(0);
            $table->timestamps();

            $table->foreign('id_ujian')
                ->references('id_ujian')
                ->on('ujians')
                ->onDelete('cascade');

            $table->foreign('id_siswa')
                ->references('id_siswa')
                ->on('siswas')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_ujians');
    }
};
