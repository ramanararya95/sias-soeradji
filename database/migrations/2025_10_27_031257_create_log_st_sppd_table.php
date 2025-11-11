<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('log_st_sppd', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat');
            $table->date('tanggal_surat');
            $table->string('perihal');
            $table->string('dasar_surat');
            $table->text('pelaksana');
            $table->string('tempat');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('kendaraan')->nullable();
            $table->string('akomodasi')->nullable();
            $table->string('pembuat_surat');
            $table->string('penandatangan');
            $table->string('file_surat')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('log_st_sppd');
    }
};