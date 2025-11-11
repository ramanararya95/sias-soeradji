<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('log_berita_pemusnahan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_berita');
            $table->date('tanggal_berita');
            $table->string('unit');
            $table->text('deskripsi_arsip');
            $table->string('jumlah');
            $table->string('alasan_pemusnahan');
            $table->string('metode_pemusnahan');
            $table->string('pelaksana');
            $table->string('saksi1');
            $table->string('saksi2');
            $table->string('file_berita')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('log_berita_pemusnahan');
    }
};