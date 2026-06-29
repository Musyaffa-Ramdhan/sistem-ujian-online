<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soal', function (Blueprint $table) {
            $table->bigIncrements('id_soal');
            $table->unsignedBigInteger('id_ujian');
            $table->text('soal');
            $table->string('opsi_a')->nullable();
            $table->string('opsi_b')->nullable();
            $table->string('opsi_c')->nullable();
            $table->string('opsi_d')->nullable();
            $table->string('opsi_e')->nullable();
            $table->string('jawaban_benar');
            $table->timestamps();

            $table->foreign('id_ujian')
                ->references('id_ujian')
                ->on('ujians');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soal');
    }
};
