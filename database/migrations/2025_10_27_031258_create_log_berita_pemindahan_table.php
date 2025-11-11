<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('log_berita_pemindahan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_berita');
            $table->date('tanggal_berita');
            $table->string('dari_unit');
            $table->string('ke_unit');
            $table->text('deskripsi_arsip');
            $table->string('jumlah');
            $table->string('penerima');
            $table->string('penyerah');
            $table->string('saksi1');
            $table->string('saksi2');
            $table->string('file_berita')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('log_berita_pemindahan');
    }
};