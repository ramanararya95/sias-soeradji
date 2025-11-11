// database/migrations/2025_10_28_120000_create_arsip_aktif_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('arsip_aktif', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_arsip')->unique();
            $table->string('kode_ka');
            $table->text('uraian_isi');
            $table->string('berkas');
            $table->string('tanggal');
            $table->string('lokasi_simpan');
            $table->string('file')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('arsip_aktif');
    }
};