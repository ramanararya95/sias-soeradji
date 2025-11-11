<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Cek apakah kolom sudah ada sebelum menambahkan
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->after('id');
            }
            
            if (!Schema::hasColumn('users', 'nama_lengkap')) {
                $table->string('nama_lengkap')->after('email');
            }
            
            if (!Schema::hasColumn('users', 'jabatan')) {
                $table->string('jabatan')->nullable()->after('nama_lengkap');
            }
            
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user')->after('jabatan');
            }
            
            if (!Schema::hasColumn('users', 'kode_petugas')) {
                $table->string('kode_petugas')->nullable()->after('role');
            }
            
            if (!Schema::hasColumn('users', 'login_ilustrasi')) {
                $table->string('login_ilustrasi')->nullable()->after('kode_petugas');
            }
            
            if (!Schema::hasColumn('users', 'reset_token')) {
                $table->string('reset_token')->nullable()->after('login_ilustrasi');
            }
            
            if (!Schema::hasColumn('users', 'reset_expires')) {
                $table->timestamp('reset_expires')->nullable()->after('reset_token');
            }
            
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->after('reset_expires');
            }
            
            if (!Schema::hasColumn('users', 'last_activity')) {
                $table->timestamp('last_activity')->nullable()->after('remember_token');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username',
                'nama_lengkap',
                'jabatan',
                'role',
                'kode_petugas',
                'login_ilustrasi',
                'reset_token',
                'reset_expires',
                'status',
                'last_activity'
            ]);
        });
    }
};