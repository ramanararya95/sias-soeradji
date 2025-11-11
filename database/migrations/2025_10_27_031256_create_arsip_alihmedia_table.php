// database/migrations/2025_10_28_120003_create_arsip_alihmedia_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('arsip_alihmedia', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_arsip')->unique();
            $table->string('organisasi');
            $table->string('unit_pengolah');
            $table->string('jenis_arsip');
            $table->string('kurun_waktu');
            $table->string('media_semula');
            $table->string('media_menjadi');
            $table->string('jumlah');
            $table->string('alat');
            $table->date('waktu');
            $table->text('keterangan')->nullable();
            $table->string('file')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('arsip_alihmedia');
    }
};