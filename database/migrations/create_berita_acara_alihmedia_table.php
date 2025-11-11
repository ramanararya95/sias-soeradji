<?php

// database/migrations/create_berita_acara_alihmedia_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('berita_acara_alihmedia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nomor');
            $table->date('tanggal');
            $table->text('media_asal');
            $table->text('media_tujuan');
            $table->text('keterangan')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('berita_acara_alihmedia');
    }
};