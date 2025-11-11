// database/migrations/2025_10_28_120001_create_arsip_inaktif_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('arsip_inaktif', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_arsip')->unique();
            $table->string('kode_ka');
            $table->text('uraian_isi');
            $table->string('tahun');
            $table->string('volume');
            $table->string('keterangan');
            $table->string('file')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('arsip_inaktif');
    }
};