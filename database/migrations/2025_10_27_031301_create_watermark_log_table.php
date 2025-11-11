<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('watermark_log', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_watermark'); // text atau image
            $table->string('file_asli');
            $table->string('file_hasil');
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('watermark_log');
    }
};