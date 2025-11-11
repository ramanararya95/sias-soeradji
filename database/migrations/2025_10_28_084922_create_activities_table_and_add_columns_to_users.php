<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Buat tabel activities
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('description');
            $table->string('type')->default('general'); // general, arsip, surat_tugas, berita_acara
            $table->json('data')->nullable(); // Data tambahan dalam format JSON
            $table->timestamps();
        });
        
        // Tambahkan kolom status ke tabel users jika belum ada
        if (!Schema::hasColumn('users', 'status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->after('password');
            });
        }
        
        // Tambahkan kolom last_activity ke tabel users jika belum ada
        if (!Schema::hasColumn('users', 'last_activity')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('last_activity')->nullable()->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
        // Kolom status dan last_activity tidak dihapus untuk menjaga integritas data
    }
};