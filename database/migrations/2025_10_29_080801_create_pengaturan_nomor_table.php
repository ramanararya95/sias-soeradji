<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengaturan_nomor', function (Blueprint $table) {
            $table->id();
            $table->integer('panjang_nomor_urut')->default(4);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengaturan_nomor');
    }
};