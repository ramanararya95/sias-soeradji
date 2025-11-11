<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('nomor_template', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_nomor');
            $table->string('format_nomor');
            $table->integer('nomor_terakhir');
            $table->string('tahun');
            $table->string('keterangan')->nullable();
            $table->string('file_template')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nomor_template');
    }
};