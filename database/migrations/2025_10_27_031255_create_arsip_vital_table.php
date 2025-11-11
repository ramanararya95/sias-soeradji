// database/migrations/2025_10_28_120002_create_arsip_vital_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('arsip_vital', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_arsip')->unique();
            $table->string('nama_instansi');
            $table->string('jenis_arsip');
            $table->string('unit_kerja');
            $table->string('kurun_waktu');
            $table->string('media');
            $table->string('jumlah');
            $table->string('jangka_simpan');
            $table->string('lokasi_simpan');
            $table->string('metode_perlindungan');
            $table->text('keterangan');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('arsip_vital');
    }
};