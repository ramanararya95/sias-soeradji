<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('nama_lengkap');
            $table->string('jabatan')->nullable();
            $table->string('role')->default('user');
            $table->string('kode_petugas')->nullable();
            $table->string('login_ilustrasi')->nullable();
            $table->string('reset_token')->nullable();
            $table->timestamp('reset_expires')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};