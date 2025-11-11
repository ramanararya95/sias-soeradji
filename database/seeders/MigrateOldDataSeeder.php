<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Illuminate\Database\QueryException;

class MigrateOldDataSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Memulai migrasi data dari sias2025 ke sias_soeradji...');
        
        try {
            // Test koneksi database lama
            $this->testOldDatabaseConnection();
            
            // Nonaktifkan foreign key checks sementara
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            // Buat tabel-tabel yang belum ada dengan struktur yang sesuai
            $this->createMissingTables();
            
            // Migrasi data users
            $this->migrateUsers();
            
            // Migrasi data arsip aktif
            $this->migrateArsipAktif();
            
            // Migrasi data arsip inaktif
            $this->migrateArsipInaktif();
            
            // Migrasi data arsip vital
            $this->migrateArsipVital();
            
            // Migrasi data arsip alihmedia
            $this->migrateArsipAlihmedia();
            
            // Migrasi data log surat tugas
            $this->migrateLogSuratTugas();
            
            // Migrasi data log st sppd
            $this->migrateLogSTSppd();
            
            // Migrasi data log berita pemindahan
            $this->migrateLogBeritaPemindahan();
            
            // Migrasi data log berita pemusnahan
            $this->migrateLogBeritaPemusnahan();
            
            // Migrasi data log berita alih media
            $this->migrateLogBeritaAlihmedia();
            
            // Aktifkan kembali foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            $this->command->info('✅ Migrasi data selesai dengan sukses!');
            
        } catch (\Exception $e) {
            $this->command->error('❌ Error saat migrasi: ' . $e->getMessage());
            $this->command->error('File: ' . $e->getFile());
            $this->command->error('Line: ' . $e->getLine());
            
            // Aktifkan kembali foreign key checks jika terjadi error
            try {
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            } catch (\Exception $e2) {
                // Ignore
            }
            
            throw $e;
        }
    }
    
    private function testOldDatabaseConnection()
    {
        try {
            $count = DB::connection('old_database')->table('users')->count();
            $this->command->info("✅ Koneksi database lama berhasil. Jumlah users: {$count}");
        } catch (\Exception $e) {
            $this->command->error('❌ Gagal terhubung ke database lama: ' . $e->getMessage());
            throw $e;
        }
    }
    
    private function createMissingTables()
    {
        $this->command->info('🔧 Membuat tabel-tabel yang belum ada...');
        
        // Daftar tabel yang perlu dibuat
        $tables = [
            'arsip_aktif' => $this->getArsipAktifTableSchema(),
            'arsip_inaktif' => $this->getArsipInaktifTableSchema(),
            'arsip_vital' => $this->getArsipVitalTableSchema(),
            'arsip_alihmedia' => $this->getArsipAlihmediaTableSchema(),
            'log_surat_tugas' => $this->getLogSuratTugasTableSchema(),
            'log_st_sppd' => $this->getLogSTSppdTableSchema(),
            'log_berita_pemindahan' => $this->getLogBeritaPemindahanTableSchema(),
            'log_berita_pemusnahan' => $this->getLogBeritaPemusnahanTableSchema(),
            'log_berita_alihmedia' => $this->getLogBeritaAlihmediaTableSchema(),
            'nomor_spreadsheet' => $this->getNomorSpreadsheetTableSchema(),
            'surat_undangan' => $this->getSuratUndanganTableSchema(),
            'nomor_template' => $this->getNomorTemplateTableSchema(),
            'watermark_log' => $this->getWatermarkLogTableSchema(),
        ];
        
        foreach ($tables as $tableName => $schema) {
            if (!Schema::hasTable($tableName)) {
                Schema::create($tableName, $schema);
                $this->command->line("   ✅ Tabel {$tableName} dibuat");
            } else {
                $this->command->line("   ⏭️  Tabel {$tableName} sudah ada");
            }
        }
    }
    
    private function migrateUsers()
    {
        $this->command->info('📋 Migrasi data users...');
        
        try {
            // Ambil data dari database lama
            $oldUsers = DB::connection('old_database')->table('users')->get();
            
            $createdCount = 0;
            $updatedCount = 0;
            $errorCount = 0;
            
            foreach ($oldUsers as $oldUser) {
                try {
                    // Cek apakah user sudah ada di database baru
                    $existingUser = DB::table('users')->where('username', $oldUser->username)->first();
                    
                    if (!$existingUser) {
                        // BUAT BARU di database baru
                        $userId = DB::table('users')->insertGetId([
                            'username' => $oldUser->username,
                            'email' => $this->generateUniqueEmail($oldUser->username, $oldUser->email),
                            'password' => $oldUser->password,
                            'nama_lengkap' => $oldUser->nama_lengkap,
                            'jabatan' => $oldUser->jabatan,
                            'role' => $oldUser->role ?? 'user',
                            'status' => $oldUser->status ?? 'aktif',
                            'kode_petugas' => $oldUser->kode_petugas,
                            'created_at' => $oldUser->created_at ?? Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                        
                        // Buat profile untuk user
                        $this->createUserProfile($userId, $oldUser);
                        
                        // Buat settings untuk user
                        $this->createUserSettings($userId);
                        
                        $createdCount++;
                        $this->command->line("   ✅ User dibuat: {$oldUser->username}");
                    } else {
                        // UPDATE user yang sudah ada
                        DB::table('users')
                            ->where('id', $existingUser->id)
                            ->update([
                                'nama_lengkap' => $oldUser->nama_lengkap,
                                'jabatan' => $oldUser->jabatan,
                                'role' => $oldUser->role ?? 'user',
                                'status' => $oldUser->status ?? 'aktif',
                                'kode_petugas' => $oldUser->kode_petugas,
                                'updated_at' => Carbon::now(),
                            ]);
                        
                        // Update profile jika ada
                        $this->updateUserProfile($existingUser->id, $oldUser);
                        
                        // Pastikan settings ada
                        $this->createUserSettings($existingUser->id);
                        
                        $updatedCount++;
                        $this->command->line("   🔄 User diupdate: {$oldUser->username}");
                    }
                } catch (QueryException $e) {
                    $errorCount++;
                    $this->command->error("   ❌ Error user {$oldUser->username}: " . $e->getMessage());
                    continue;
                }
            }
            
            $this->command->info("   📊 Users: {$createdCount} dibuat, {$updatedCount} diupdate, {$errorCount} error");
            
        } catch (\Exception $e) {
            $this->command->error('❌ Error di migrateUsers: ' . $e->getMessage());
            throw $e;
        }
    }
    
    private function generateUniqueEmail($username, $originalEmail)
    {
        // Jika email asli unik, gunakan email asli
        if (!DB::table('users')->where('email', $originalEmail)->exists()) {
            return $originalEmail;
        }
        
        // Jika email duplikat, buat email unik berdasarkan username
        $baseEmail = $username . '@sias.local';
        $counter = 1;
        $email = $baseEmail;
        
        while (DB::table('users')->where('email', $email)->exists()) {
            $email = $username . $counter . '@sias.local';
            $counter++;
        }
        
        return $email;
    }
    
    private function createUserProfile($userId, $oldUser = null)
    {
        if (!DB::table('user_profiles')->where('user_id', $userId)->exists()) {
            $profileData = [
                'user_id' => $userId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            
            // Jika ada data dari user lama, tambahkan ke profile
            if ($oldUser && isset($oldUser->foto)) {
                $profileData['foto'] = $oldUser->foto;
            }
            
            DB::table('user_profiles')->insert($profileData);
        } else if ($oldUser && isset($oldUser->foto)) {
            // Update foto jika ada
            DB::table('user_profiles')
                ->where('user_id', $userId)
                ->update([
                    'foto' => $oldUser->foto,
                    'updated_at' => Carbon::now(),
                ]);
        }
    }
    
    private function updateUserProfile($userId, $oldUser)
    {
        if (DB::table('user_profiles')->where('user_id', $userId)->exists()) {
            $profileData = [
                'updated_at' => Carbon::now(),
            ];
            
            // Jika ada data dari user lama, tambahkan ke profile
            if (isset($oldUser->foto)) {
                $profileData['foto'] = $oldUser->foto;
            }
            
            DB::table('user_profiles')
                ->where('user_id', $userId)
                ->update($profileData);
        } else {
            $this->createUserProfile($userId, $oldUser);
        }
    }
    
    private function createUserSettings($userId)
    {
        if (!DB::table('user_settings')->where('user_id', $userId)->exists()) {
            DB::table('user_settings')->insert([
                'user_id' => $userId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
    
    private function migrateArsipAktif()
    {
        $this->command->info('📁 Migrasi data arsip aktif...');
        
        try {
            // Cek apakah tabel arsip_aktif ada di database lama
            if (!$this->tableExists('old_database', 'arsip_aktif')) {
                $this->command->warn('   ⚠️  Tabel arsip_aktif tidak ditemukan di database lama, dilewati.');
                return;
            }
            
            // Ambil data dari database lama
            $oldArsip = DB::connection('old_database')->table('arsip_aktif')->get();
            
            $migratedCount = 0;
            $skippedCount = 0;
            $errorCount = 0;
            
            foreach ($oldArsip as $old) {
                try {
                    // Cari user ID yang sesuai
                    $user = DB::table('users')->where('username', $old->user_id)->first();
                    $userId = $user ? $user->id : 1;
                    
                    // Cek apakah data sudah ada
                    $existing = DB::table('arsip_aktif')
                        ->where('nomor_arsip', $old->nomor_arsip)
                        ->first();
                    
                    if (!$existing) {
                        // Siapkan data untuk insert
                        $data = [
                            'user_id' => $userId,
                            'nomor_arsip' => $old->nomor_arsip,
                            'kode_ka' => $old->kode_ka,
                            'berkas' => $old->berkas,
                            'uraian_isi' => $old->uraian_isi,
                            'tanggal' => $old->tanggal,
                            'lokasi_simpan' => $old->lokasi_simpan,
                            'file' => $old->file,
                            'status' => $old->status ?? 'active',
                            'created_at' => $old->created_at ?? Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                        
                        // Filter out null values
                        $data = array_filter($data, function($value) {
                            return $value !== null;
                        });
                        
                        DB::table('arsip_aktif')->insert($data);
                        
                        $migratedCount++;
                        $this->command->line("   ✅ Arsip aktif dipindah: {$old->nomor_arsip}");
                    } else {
                        $skippedCount++;
                        $this->command->line("   ⏭️  Arsip aktif sudah ada: {$old->nomor_arsip}");
                    }
                } catch (QueryException $e) {
                    $errorCount++;
                    $this->command->error("   ❌ Error arsip {$old->nomor_arsip}: " . $e->getMessage());
                    continue;
                }
            }
            
            $this->command->info("   📊 Arsip Aktif: {$migratedCount} dipindah, {$skippedCount} dilewati, {$errorCount} error");
            
        } catch (\Exception $e) {
            $this->command->error('❌ Error di migrateArsipAktif: ' . $e->getMessage());
            throw $e;
        }
    }
    
    private function migrateArsipInaktif()
    {
        $this->command->info('📁 Migrasi data arsip inaktif...');
        
        try {
            // Cek apakah tabel ada di database lama
            if (!$this->tableExists('old_database', 'arsip_inaktif')) {
                $this->command->warn('   ⚠️  Tabel arsip_inaktif tidak ditemukan di database lama, dilewati.');
                return;
            }
            
            $oldArsip = DB::connection('old_database')->table('arsip_inaktif')->get();
            
            $migratedCount = 0;
            $skippedCount = 0;
            $errorCount = 0;
            
            foreach ($oldArsip as $old) {
                try {
                    // Cari user ID yang sesuai
                    $user = DB::table('users')->where('username', $old->user_id)->first();
                    $userId = $user ? $user->id : 1;
                    
                    // Cek apakah data sudah ada
                    $existing = DB::table('arsip_inaktif')
                        ->where('nomor_arsip', $old->nomor_arsip)
                        ->first();
                    
                    if (!$existing) {
                        // Siapkan data untuk insert
                        $data = [
                            'user_id' => $userId,
                            'nomor_arsip' => $old->nomor_arsip,
                            'kode_ka' => $old->kode_ka,
                            'berkas' => $old->berkas,
                            'uraian_isi' => $old->uraian_isi,
                            'tanggal' => $old->tanggal,
                            'lokasi_simpan' => $old->lokasi_simpan,
                            'file' => $old->file,
                            'status' => $old->status ?? 'active',
                            'created_at' => $old->created_at ?? Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                        
                        // Filter out null values
                        $data = array_filter($data, function($value) {
                            return $value !== null;
                        });
                        
                        DB::table('arsip_inaktif')->insert($data);
                        
                        $migratedCount++;
                        $this->command->line("   ✅ Arsip inaktif dipindah: {$old->nomor_arsip}");
                    } else {
                        $skippedCount++;
                        $this->command->line("   ⏭️  Arsip inaktif sudah ada: {$old->nomor_arsip}");
                    }
                } catch (QueryException $e) {
                    $errorCount++;
                    $this->command->error("   ❌ Error arsip inaktif {$old->nomor_arsip}: " . $e->getMessage());
                    continue;
                }
            }
            
            $this->command->info("   📊 Arsip Inaktif: {$migratedCount} dipindah, {$skippedCount} dilewati, {$errorCount} error");
            
        } catch (\Exception $e) {
            $this->command->error('❌ Error di migrateArsipInaktif: ' . $e->getMessage());
            throw $e;
        }
    }
    
    private function migrateArsipVital()
    {
        $this->command->info('📁 Migrasi data arsip vital...');
        
        try {
            // Cek apakah tabel ada di database lama
            if (!$this->tableExists('old_database', 'arsip_vital')) {
                $this->command->warn('   ⚠️  Tabel arsip_vital tidak ditemukan di database lama, dilewati.');
                return;
            }
            
            $oldArsip = DB::connection('old_database')->table('arsip_vital')->get();
            
            $migratedCount = 0;
            $skippedCount = 0;
            $errorCount = 0;
            
            foreach ($oldArsip as $old) {
                try {
                    // Cari user ID yang sesuai
                    $user = DB::table('users')->where('username', $old->user_id)->first();
                    $userId = $user ? $user->id : 1;
                    
                    // Cek apakah data sudah ada
                    $existing = DB::table('arsip_vital')
                        ->where('nomor_arsip', $old->nomor_arsip)
                        ->first();
                    
                    if (!$existing) {
                        // Siapkan data untuk insert
                        $data = [
                            'user_id' => $userId,
                            'nomor_arsip' => $old->nomor_arsip,
                            'kode_ka' => $old->kode_ka,
                            'berkas' => $old->berkas,
                            'uraian_isi' => $old->uraian_isi,
                            'tanggal' => $old->tanggal,
                            'lokasi_simpan' => $old->lokasi_simpan,
                            'file' => $old->file,
                            'status' => $old->status ?? 'active',
                            'created_at' => $old->created_at ?? Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                        
                        // Filter out null values
                        $data = array_filter($data, function($value) {
                            return $value !== null;
                        });
                        
                        DB::table('arsip_vital')->insert($data);
                        
                        $migratedCount++;
                        $this->command->line("   ✅ Arsip vital dipindah: {$old->nomor_arsip}");
                    } else {
                        $skippedCount++;
                        $this->command->line("   ⏭️  Arsip vital sudah ada: {$old->nomor_arsip}");
                    }
                } catch (QueryException $e) {
                    $errorCount++;
                    $this->command->error("   ❌ Error arsip vital {$old->nomor_arsip}: " . $e->getMessage());
                    continue;
                }
            }
            
            $this->command->info("   📊 Arsip Vital: {$migratedCount} dipindah, {$skippedCount} dilewati, {$errorCount} error");
            
        } catch (\Exception $e) {
            $this->command->error('❌ Error di migrateArsipVital: ' . $e->getMessage());
            throw $e;
        }
    }
    
    private function migrateArsipAlihmedia()
    {
        $this->command->info('🔄 Migrasi data arsip alih media...');
        
        try {
            // Cek apakah tabel ada di database lama
            if (!$this->tableExists('old_database', 'arsip_alihmedia')) {
                $this->command->warn('   ⚠️  Tabel arsip_alihmedia tidak ditemukan di database lama, dilewati.');
                return;
            }
            
            $oldArsip = DB::connection('old_database')->table('arsip_alihmedia')->get();
            
            $migratedCount = 0;
            $skippedCount = 0;
            $errorCount = 0;
            
            foreach ($oldArsip as $old) {
                try {
                    // Cari user ID yang sesuai
                    $user = DB::table('users')->where('username', $old->user_id)->first();
                    $userId = $user ? $user->id : 1;
                    
                    // Cek apakah data sudah ada
                    $existing = DB::table('arsip_alihmedia')
                        ->where('nomor_arsip', $old->nomor_arsip)
                        ->first();
                    
                    if (!$existing) {
                        // Siapkan data untuk insert
                        $data = [
                            'user_id' => $userId,
                            'nomor_arsip' => $old->nomor_arsip,
                            'kode_ka' => $old->kode_ka,
                            'berkas' => $old->berkas,
                            'uraian_isi' => $old->uraian_isi,
                            'tanggal' => $old->tanggal,
                            'lokasi_simpan' => $old->lokasi_simpan,
                            'file' => $old->file,
                            'status' => $old->status ?? 'active',
                            'created_at' => $old->created_at ?? Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                        
                        // Filter out null values
                        $data = array_filter($data, function($value) {
                            return $value !== null;
                        });
                        
                        DB::table('arsip_alihmedia')->insert($data);
                        
                        $migratedCount++;
                        $this->command->line("   ✅ Arsip alih media dipindah: {$old->nomor_arsip}");
                    } else {
                        $skippedCount++;
                        $this->command->line("   ⏭️  Arsip alih media sudah ada: {$old->nomor_arsip}");
                    }
                } catch (QueryException $e) {
                    $errorCount++;
                    $this->command->error("   ❌ Error arsip alih media {$old->nomor_arsip}: " . $e->getMessage());
                    continue;
                }
            }
            
            $this->command->info("   📊 Arsip Alih Media: {$migratedCount} dipindah, {$skippedCount} dilewati, {$errorCount} error");
            
        } catch (\Exception $e) {
            $this->command->error('❌ Error di migrateArsipAlihmedia: ' . $e->getMessage());
            throw $e;
        }
    }
    
    private function migrateLogSuratTugas()
    {
        $this->command->info('📄 Migrasi data log surat tugas...');
        
        try {
            // Cek apakah tabel ada di database lama
            if (!$this->tableExists('old_database', 'log_surat_tugas')) {
                $this->command->warn('   ⚠️  Tabel log_surat_tugas tidak ditemukan di database lama, dilewati.');
                return;
            }
            
            $oldData = DB::connection('old_database')->table('log_surat_tugas')->get();
            
            $migratedCount = 0;
            $skippedCount = 0;
            $errorCount = 0;
            
            foreach ($oldData as $old) {
                try {
                    // Cari user ID yang sesuai - tidak ada user_id di tabel ini, jadi gunakan id dari tabel users
                    // Karena tidak ada user_id, kita skip migrasi ini atau gunakan admin sebagai default
                    $userId = 1; // Default ke admin
                    
                    // Cek apakah data sudah ada
                    $existing = DB::table('log_surat_tugas')
                        ->where('nomor_naskah', $old->nomor_naskah)
                        ->first();
                    
                    if (!$existing) {
                        // Siapkan data untuk insert
                        $data = [
                            'nomor_naskah' => $old->nomor_naskah,
                            'jenis_naskah' => $old->jenis_naskah,
                            'pengirim' => $old->pengirim,
                            'nomor_pengirim' => $old->nomor_pengirim,
                            'tanggal_surat' => $old->tanggal_surat,
                            'hal' => $old->hal,
                            'tanggal_naskah' => $old->tanggal_naskah,
                            'jabatan_pengirim' => $old->jabatan_pengirim,
                            'ttd_pengirim' => $old->ttd_pengirim,
                            'nama_pengirim' => $old->nama_pengirim,
                            'nama_gelar1' => $old->nama_gelar1,
                            'nip1' => $old->nip1,
                            'pangkat_golongan1' => $old->pangkat_golongan1,
                            'jabatan1' => $old->jabatan1,
                            'nama_gelar2' => $old->nama_gelar2,
                            'nip2' => $old->nip2,
                            'pangkat_golongan2' => $old->pangkat_golongan2,
                            'jabatan2' => $old->jabatan2,
                            'untuk_1' => $old->untuk_1,
                            'untuk_2' => $old->untuk_2,
                            'untuk_3' => $old->untuk_3,
                            'untuk_4' => $old->untuk_4,
                            'untuk_5' => $old->untuk_5,
                            'untuk_6' => $old->untuk_6,
                            'filename' => $old->filename,
                            'filename_word' => $old->filename_word,
                            'template_type' => $old->template_type,
                            'html' => $old->html,
                            'tanggal_simpan' => $old->tanggal_simpan,
                            'created_at' => $old->created_at ?? Carbon::now(),
                            'nama_gelar3' => $old->nama_gelar3,
                            'nip3' => $old->nip3,
                            'pangkat_golongan3' => $old->pangkat_golongan3,
                            'jabatan3' => $old->jabatan3,
                            'nama_gelar4' => $old->nama_gelar4,
                            'nip4' => $old->nip4,
                            'pangkat_golongan4' => $old->pangkat_golongan4,
                            'jabatan4' => $old->jabatan4,
                            'nama_gelar5' => $old->nama_gelar5,
                            'nip5' => $old->nip5,
                            'pangkat_golongan5' => $old->pangkat_golongan5,
                            'jabatan5' => $old->jabatan5,
                            'nama_gelar6' => $old->nama_gelar6,
                            'nip6' => $old->nip6,
                            'pangkat_golongan6' => $old->pangkat_golongan6,
                            'jabatan6' => $old->jabatan6,
                            'nama_gelar7' => $old->nama_gelar7,
                            'nip7' => $old->nip7,
                            'pangkat_golongan7' => $old->pangkat_golongan7,
                            'jabatan7' => $old->jabatan7,
                            'nama_gelar8' => $old->nama_gelar8,
                            'nip8' => $old->nip8,
                            'pangkat_golongan8' => $old->pangkat_golongan8,
                            'jabatan8' => $old->jabatan8,
                            'nama_gelar9' => $old->nama_gelar9,
                            'nip9' => $old->nip9,
                            'pangkat_golongan9' => $old->pangkat_golongan9,
                            'jabatan9' => $old->jabatan9,
                            'nama_gelar10' => $old->nama_gelar10,
                            'nip10' => $old->nip10,
                            'pangkat_golongan10' => $old->pangkat_golongan10,
                            'jabatan10' => $old->jabatan10,
                            'nama_gelar11' => $old->nama_gelar11,
                            'nip11' => $old->nip11,
                            'pangkat_golongan11' => $old->pangkat_golongan11,
                            'jabatan11' => $old->jabatan11,
                            'nama_gelar12' => $old->nama_gelar12,
                            'nip12' => $old->nip12,
                            'pangkat_golongan12' => $old->pangkat_golongan12,
                            'jabatan12' => $old->jabatan12,
                            'nama_gelar13' => $old->nama_gelar13,
                            'nip13' => $old->nip13,
                            'pangkat_golongan13' => $old->pangkat_golongan13,
                            'jabatan13' => $old->jabatan13,
                            'nama_gelar14' => $old->nama_gelar14,
                            'nip14' => $old->nip14,
                            'pangkat_golongan14' => $old->pangkat_golongan14,
                            'jabatan14' => $old->jabatan14,
                            'nama_gelar15' => $old->nama_gelar15,
                            'nip15' => $old->nip15,
                            'pangkat_golongan15' => $old->pangkat_golongan15,
                            'jabatan15' => $old->jabatan15,
                            'nama_gelar16' => $old->nama_gelar16,
                            'nip16' => $old->nip16,
                            'pangkat_golongan16' => $old->pangkat_golongan16,
                            'jabatan16' => $old->jabatan16,
                            'nama_gelar17' => $old->nama_gelar17,
                            'nip17' => $old->nip17,
                            'pangkat_golongan17' => $old->pangkat_golongan17,
                            'jabatan17' => $old->jabatan17,
                            'nama_gelar18' => $old->nama_gelar18,
                            'nip18' => $old->nip18,
                            'pangkat_golongan18' => $old->pangkat_golongan18,
                            'jabatan18' => $old->jabatan18,
                            'nama_gelar19' => $old->nama_gelar19,
                            'nip19' => $old->nip19,
                            'pangkat_golongan19' => $old->pangkat_golongan19,
                            'jabatan19' => $old->jabatan19,
                            'nama_gelar20' => $old->nama_gelar20,
                            'nip20' => $old->nip20,
                            'pangkat_golongan20' => $old->pangkat_golongan20,
                            'jabatan20' => $old->jabatan20,
                            'nama_gelar21' => $old->nama_gelar21,
                            'nip21' => $old->nip21,
                            'pangkat_golongan21' => $old->pangkat_golongan21,
                            'jabatan21' => $old->jabatan21,
                            'nama_gelar22' => $old->nama_gelar22,
                            'nip22' => $old->nip22,
                            'pangkat_golongan22' => $old->pangkat_golongan22,
                            'jabatan22' => $old->jabatan22,
                            'nama_gelar23' => $old->nama_gelar23,
                            'nip23' => $old->nip23,
                            'pangkat_golongan23' => $old->pangkat_golongan23,
                            'jabatan23' => $old->jabatan23,
                            'nama_gelar24' => $old->nama_gelar24,
                            'nip24' => $old->nip24,
                            'pangkat_golongan24' => $old->pangkat_golongan24,
                            'jabatan24' => $old->jabatan24,
                            'nama_gelar25' => $old->nama_gelar25,
                            'nip25' => $old->nip25,
                            'pangkat_golongan25' => $old->pangkat_golongan25,
                            'jabatan25' => $old->jabatan25,
                            'nama_gelar26' => $old->nama_gelar26,
                            'nip26' => $old->nip26,
                            'pangkat_golongan26' => $old->pangkat_golongan26,
                            'jabatan26' => $old->jabatan26,
                            'nama_gelar27' => $old->nama_gelar27,
                            'nip27' => $old->nip27,
                            'pangkat_golongan27' => $old->pangkat_golongan27,
                            'jabatan27' => $old->jabatan27,
                            'nama_gelar28' => $old->nama_gelar28,
                            'nip28' => $old->nip28,
                            'pangkat_golongan28' => $old->pangkat_golongan28,
                            'jabatan28' => $old->jabatan28,
                            'nama_gelar29' => $old->nama_gelar29,
                            'nip29' => $old->nip29,
                            'pangkat_golongan29' => $old->pangkat_golongan29,
                            'jabatan29' => $old->jabatan29,
                            'nama_gelar30' => $old->nama_gelar30,
                            'nip30' => $old->nip30,
                            'pangkat_golongan30' => $old->pangkat_golongan30,
                            'jabatan30' => $old->jabatan30,
                            'user_id' => $userId,
                            'created_at' => $old->created_at ?? Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                        
                        // Filter out null values
                        $data = array_filter($data, function($value) {
                            return $value !== null;
                        });
                        
                        DB::table('log_surat_tugas')->insert($data);
                        
                        $migratedCount++;
                        $this->command->line("   ✅ Surat tugas dipindah: {$old->nomor_naskah}");
                    } else {
                        $skippedCount++;
                        $this->command->line("   ⏭️  Surat tugas sudah ada: {$old->nomor_naskah}");
                    }
                } catch (QueryException $e) {
                    $errorCount++;
                    $this->command->error("   ❌ Error surat tugas {$old->nomor_naskah}: " . $e->getMessage());
                    continue;
                }
            }
            
            $this->command->info("   📊 Surat Tugas: {$migratedCount} dipindah, {$skippedCount} dilewati, {$errorCount} error");
            
        } catch (\Exception $e) {
            $this->command->error('❌ Error di migrateLogSuratTugas: ' . $e->getMessage());
            throw $e;
        }
    }
    
    private function migrateLogSTSppd()
    {
        $this->command->info('📄 Migrasi data log ST SPPD...');
        
        try {
            // Cek apakah tabel ada di database lama
            if (!$this->tableExists('old_database', 'log_st_sppd')) {
                $this->command->warn('   ⚠️  Tabel log_st_sppd tidak ditemukan di database lama, dilewati.');
                return;
            }
            
            $oldData = DB::connection('old_database')->table('log_st_sppd')->get();
            
            $migratedCount = 0;
            $skippedCount = 0;
            $errorCount = 0;
            
            foreach ($oldData as $old) {
                try {
                    // Cari user ID yang sesuai
                    $user = DB::table('users')->where('username', $old->user_id)->first();
                    $userId = $user ? $user->id : 1;
                    
                    // Cek apakah data sudah ada
                    $existing = DB::table('log_st_sppd')
                        ->where('nomor_surat', $old->nomor_surat)
                        ->first();
                    
                    if (!$existing) {
                        // Siapkan data untuk insert
                        $data = [
                            'nomor_surat' => $old->nomor_surat,
                            'tanggal_surat' => $old->tanggal_surat,
                            'perihal' => $old->perihal,
                            'dasar_surat' => $old->dasar_surat,
                            'pelaksana' => $old->pelaksana,
                            'tempat' => $old->tempat,
                            'tanggal_mulai' => $old->tanggal_mulai,
                            'tanggal_selesai' => $old->tanggal_selesai,
                            'pembuat_surat' => $old->pembuat_surat,
                            'penandatangan' => $old->penandatangan,
                            'file_surat' => $old->file_surat,
                            'user_id' => $userId,
                            'created_at' => $old->created_at ?? Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                        
                        // Filter out null values
                        $data = array_filter($data, function($value) {
                            return $value !== null;
                        });
                        
                        DB::table('log_st_sppd')->insert($data);
                        
                        $migratedCount++;
                        $this->command->line("   ✅ ST SPPD dipindah: {$old->nomor_surat}");
                    } else {
                        $skippedCount++;
                        $this->command->line("   ⏭️  ST SPPD sudah ada: {$old->nomor_surat}");
                    }
                } catch (QueryException $e) {
                    $errorCount++;
                    $this->command->error("   ❌ Error ST SPPD {$old->nomor_surat}: " . $e->getMessage());
                    continue;
                }
            }
            
            $this->command->info("   📊 ST SPPD: {$migratedCount} dipindah, {$skippedCount} dilewati, {$errorCount} error");
            
        } catch (\Exception $e) {
            $this->command->error('❌ Error di migrateLogSTSppd: ' . $e->getMessage());
            throw $e;
        }
    }
    
    private function migrateLogBeritaPemindahan()
    {
        $this->command->info('📋 Migrasi data log berita pemindahan...');
        
        try {
            if (!$this->tableExists('old_database', 'log_berita_pemindahan')) {
                $this->command->warn('   ⚠️  Tabel log_berita_pemindahan tidak ditemukan di database lama, dilewati.');
                return;
            }
            
            $oldData = DB::connection('old_database')->table('log_berita_pemindahan')->get();
            
            $migratedCount = 0;
            $skippedCount = 0;
            $errorCount = 0;
            
            foreach ($oldData as $old) {
                try {
                    $user = DB::table('users')->where('username', $old->user_id)->first();
                    $userId = $user ? $user->id : 1;
                    
                    $existing = DB::table('log_berita_pemindahan')
                        ->where('nomor_berita', $old->nomor_berita)
                        ->first();
                    
                    if (!$existing) {
                        $data = [
                            'nomor_berita' => $old->nomor_berita,
                            'tanggal_berita' => $old->tanggal_berita,
                            'dari_unit' => $old->dari_unit,
                            'ke_unit' => $old->ke_unit,
                            'deskripsi_arsip' => $old->deskripsi_arsip,
                            'jumlah' => $old->jumlah,
                            'penerima' => $old->penerima,
                            'penyerah' => $old->penyerah,
                            'saksi1' => $old->saksi1,
                            'saksi2' => $old->saksi2,
                            'file_berita' => $old->file_berita,
                            'user_id' => $userId,
                            'created_at' => $old->created_at ?? Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                        
                        $data = array_filter($data, function($value) {
                            return $value !== null;
                        });
                        
                        DB::table('log_berita_pemindahan')->insert($data);
                        
                        $migratedCount++;
                        $this->command->line("   ✅ Berita pemindahan dipindah: {$old->nomor_berita}");
                    } else {
                        $skippedCount++;
                        $this->command->line("   ⏭️  Berita pemindahan sudah ada: {$old->nomor_berita}");
                    }
                } catch (QueryException $e) {
                    $errorCount++;
                    $this->command->error("   ❌ Error berita pemindahan {$old->nomor_berita}: " . $e->getMessage());
                    continue;
                }
            }
            
            $this->command->info("   📊 Berita Pemindahan: {$migratedCount} dipindah, {$skippedCount} dilewati, {$errorCount} error");
            
        } catch (\Exception $e) {
            $this->command->error('❌ Error di migrateLogBeritaPemindahan: ' . $e->getMessage());
            throw $e;
        }
    }
    
    private function migrateLogBeritaPemusnahan()
    {
        $this->command->info('🗑️  Migrasi data log berita pemusnahan...');
        
        try {
            if (!$this->tableExists('old_database', 'log_berita_pemusnahan')) {
                $this->command->warn('   ⚠️  Tabel log_berita_pemusnahan tidak ditemukan di database lama, dilewati.');
                return;
            }
            
            $oldData = DB::connection('old_database')->table('log_berita_pemusnahan')->get();
            
            $migratedCount = 0;
            $skippedCount = 0;
            $errorCount = 0;
            
            foreach ($oldData as $old) {
                try {
                    $user = DB::table('users')->where('username', $old->user_id)->first();
                    $userId = $user ? $user->id : 1;
                    
                    $existing = DB::table('log_berita_pemusnahan')
                        ->where('nomor_berita', $old->nomor_berita)
                        ->first();
                    
                    if (!$existing) {
                        $data = [
                            'nomor_berita' => $old->nomor_berita,
                            'tanggal_berita' => $old->tanggal_berita,
                            'unit' => $old->unit,
                            'deskripsi_arsip' => $old->deskripsi_arsip,
                            'jumlah' => $old->jumlah,
                            'alasan_pemusnahan' => $old->alasan_pemusnahan,
                            'metode_pemusnahan' => $old->metode_pemusnahan,
                            'pelaksana' => $old->pelaksana,
                            'saksi1' => $old->saksi1,
                            'saksi2' => $old->saksi2,
                            'file_berita' => $old->file_berita,
                            'user_id' => $userId,
                            'created_at' => $old->created_at ?? Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                        
                        $data = array_filter($data, function($value) {
                            return $value !== null;
                        });
                        
                        DB::table('log_berita_pemusnahan')->insert($data);
                        
                        $migratedCount++;
                        $this->command->line("   ✅ Berita pemusnahan dipindah: {$old->nomor_berita}");
                    } else {
                        $skippedCount++;
                        $this->command->line("   ⏭️  Berita pemusnahan sudah ada: {$old->nomor_berita}");
                    }
                } catch (QueryException $e) {
                    $errorCount++;
                    $this->command->error("   ❌ Error berita pemusnahan {$old->nomor_berita}: " . $e->getMessage());
                    continue;
                }
            }
            
            $this->command->info("   📊 Berita Pemusnahan: {$migratedCount} dipindah, {$skippedCount} dilewati, {$errorCount} error");
            
        } catch (\Exception $e) {
            $this->command->error('❌ Error di migrateLogBeritaPemusnahan: ' . $e->getMessage());
            throw $e;
        }
    }
    
    private function migrateLogBeritaAlihmedia()
    {
        $this->command->info('🔄 Migrasi data log berita alih media...');
        
        try {
            if (!$this->tableExists('old_database', 'log_berita_alihmedia')) {
                $this->command->warn('   ⚠️  Tabel log_berita_alihmedia tidak ditemukan di database lama, dilewati.');
                return;
            }
            
            $oldData = DB::connection('old_database')->table('log_berita_alihmedia')->get();
            
            $migratedCount = 0;
            $skippedCount = 0;
            $errorCount = 0;
            
            foreach ($oldData as $old) {
                try {
                    $user = DB::table('users')->where('username', $old->user_id)->first();
                    $userId = $user ? $user->id : 1;
                    
                    $existing = DB::table('log_berita_alihmedia')
                        ->where('nomor_berita', $old->nomor_berita)
                        ->first();
                    
                    if (!$existing) {
                        $data = [
                            'nomor_berita' => $old->nomor_berita,
                            'tanggal_berita' => $old->tanggal_berita,
                            'unit' => $old->unit,
                            'deskripsi_arsip' => $old->deskripsi_arsip,
                            'jumlah' => $old->jumlah,
                            'media_asli' => $old->media_asli,
                            'media_tujuan' => $old->media_tujuan,
                            'alasan_alihmedia' => $old->alasan_alihmedia,
                            'pelaksana' => $old->pelaksana,
                            'saksi1' => $old->saksi1,
                            'saksi2' => $old->saksi2,
                            'file_berita' => $old->file_berita,
                            'user_id' => $userId,
                            'created_at' => $old->created_at ?? Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                        
                        $data = array_filter($data, function($value) {
                            return $value !== null;
                        });
                        
                        DB::table('log_berita_alihmedia')->insert($data);
                        
                        $migratedCount++;
                        $this->command->line("   ✅ Berita alih media dipindah: {$old->nomor_berita}");
                    } else {
                        $skippedCount++;
                        $this->command->line("   ⏭️  Berita alih media sudah ada: {$old->nomor_berita}");
                    }
                } catch (QueryException $e) {
                    $errorCount++;
                    $this->command->error("   ❌ Error berita alih media {$old->nomor_berita}: " . $e->getMessage());
                    continue;
                }
            }
            
            $this->command->info("   📊 Berita Alih Media: {$migratedCount} dipindah, {$skippedCount} dilewati, {$errorCount} error");
            
        } catch (\Exception $e) {
            $this->command->error('❌ Error di migrateLogBeritaAlihmedia: ' . $e->getMessage());
            throw $e;
        }
    }
    
    // Helper function untuk mengecek apakah tabel ada
    private function tableExists($connection, $table)
    {
        try {
            $tables = DB::connection($connection)->select("SHOW TABLES LIKE '{$table}'");
            return count($tables) > 0;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    // Schema untuk tabel-tabel yang akan dibuat
    private function getArsipAktifTableSchema()
    {
        return function ($table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('nomor_arsip');
            $table->string('kode_ka');
            $table->string('berkas');
            $table->text('uraian_isi')->nullable();
            $table->string('tanggal')->nullable();
            $table->string('lokasi_simpan')->nullable();
            $table->string('file')->nullable();
            $table->enum('status', ['active', 'inactive', 'deleted'])->default('active');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        };
    }
    
    private function getArsipInaktifTableSchema()
    {
        return function ($table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('nomor_arsip');
            $table->string('kode_ka');
            $table->string('berkas');
            $table->text('uraian_isi')->nullable();
            $table->string('tanggal')->nullable();
            $table->string('lokasi_simpan')->nullable();
            $table->string('file')->nullable();
            $table->enum('status', ['active', 'inactive', 'deleted'])->default('active');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        };
    }
    
    private function getArsipVitalTableSchema()
    {
        return function ($table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('nomor_arsip');
            $table->string('kode_ka');
            $table->string('berkas');
            $table->text('uraian_isi')->nullable();
            $table->string('tanggal')->nullable();
            $table->string('lokasi_simpan')->nullable();
            $table->string('file')->nullable();
            $table->enum('status', ['active', 'inactive', 'deleted'])->default('active');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        };
    }
    
    private function getArsipAlihmediaTableSchema()
    {
        return function ($table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('nomor_arsip');
            $table->string('kode_ka');
            $table->string('berkas');
            $table->text('uraian_isi')->nullable();
            $table->string('tanggal')->nullable();
            $table->string('lokasi_simpan')->nullable();
            $table->string('file')->nullable();
            $table->enum('status', ['active', 'inactive', 'deleted'])->default('active');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        };
    }
    
    private function getLogSuratTugasTableSchema()
    {
        return function ($table) {
            $table->id();
            $table->string('nomor_naskah');
            $table->string('jenis_naskah');
            $table->string('pengirim');
            $table->string('nomor_pengirim');
            $table->text('tanggal_surat');
            $table->text('hal');
            $table->string('tanggal_naskah');
            $table->string('jabatan_pengirim');
            $table->string('ttd_pengirim');
            $table->string('nama_pengirim');
            $table->string('nama_gelar1');
            $table->string('nip1');
            $table->string('pangkat_golongan1');
            $table->string('jabatan1');
            $table->string('nama_gelar2');
            $table->string('nip2');
            $table->string('pangkat_golongan2');
            $table->string('jabatan2');
            $table->text('untuk_1');
            $table->text('untuk_2');
            $table->text('untuk_3');
            $table->text('untuk_4');
            $table->text('untuk_5');
            $table->text('untuk_6');
            $table->string('filename');
            $table->string('filename_word');
            $table->string('template_type');
            $table->longText('html');
            $table->datetime('tanggal_simpan');
            $table->timestamps();
            $table->string('nama_gelar3');
            $table->string('nip3');
            $table->string('pangkat_golongan3');
            $table->string('jabatan3');
            $table->string('nama_gelar4');
            $table->string('nip4');
            $table->string('pangkat_golongan4');
            $table->string('jabatan4');
            $table->string('nama_gelar5');
            $table->string('nip5');
            $table->string('pangkat_golongan5');
            $table->string('jabatan5');
            $table->string('nama_gelar6');
            $table->string('nip6');
            $table->string('pangkat_golongan6');
            $table->string('jabatan6');
            $table->string('nama_gelar7');
            $table->string('nip7');
            $table->string('pangkat_golongan7');
            $table->string('jabatan7');
            $table->string('nama_gelar8');
            $table->string('nip8');
            $table->string('pangkat_golongan8');
            $table->string('jabatan8');
            $table->string('nama_gelar9');
            $table->string('nip9');
            $table->string('pangkat_golongan9');
            $table->string('jabatan9');
            $table->string('nama_gelar10');
            $table->string('nip10');
            $table->string('pangkat_golongan10');
            $table->string('jabatan10');
            $table->string('nama_gelar11');
            $table->string('nip11');
            $table->string('pangkat_golongan11');
            $table->string('jabatan11');
            $table->string('nama_gelar12');
            $table->string('nip12');
            $table->string('pangkat_golongan12');
            $table->string('jabatan12');
            $table->string('nama_gelar13');
            $table->string('nip13');
            $table->string('pangkat_golongan13');
            $table->string('jabatan13');
            $table->string('nama_gelar14');
            $table->string('nip14');
            $table->string('pangkat_golongan14');
            $table->string('jabatan14');
            $table->string('nama_gelar15');
            $table->text('nama_gelar15');
            $table->text('nama_gelar16');
            $table->text('nama_gelar17');
            $table->text('nama_gelar18');
            $table->text('nama_gelar19');
            $table->text('nama_gelar20');
            $table->text('nama_gelar21');
            $table->text('nama_gelar22');
            $table->text('nama_gelar23');
            $table->text('nama_gelar24');
            $table->text('nama_gelar25');
            $table->text('nama_gelar26');
            $table->text('nama_gelar27');
            $table->text('nama_gelar28');
            $text('nama_gelar29');
            $text('nama_gelar30');
            $table->text('nip15');
            $table->text('nip16');
            $table->text('nip17');
            $table->text('nip18');
            $table->text('nip19');
            $table->text('nip20');
            $table->text('nip21');
            $table->text('nip22');
            $table->text('nip23');
            $table->text('nip24');
            $table->text('nip25');
            $table->text('nip26');
            $table->text('nip27');
            $table->text('nip28');
            $table->text('nip29');
            $table->text('nip30');
            $table->text('pangkat_golongan15');
            $table->text('pangkat_golongan16');
            $table->text('pangkat_golongan17');
            $table->text('pangkat_golongan18');
            $table->text('pangkat_golongan19');
            $table->text('pangkat_golongan20');
            $table->text('pangkat_golongan21');
            $table->text('pangkat_golongan22');
            $table->text('pangkat_golongan23');
            $table->text('pangkat_golongan27');
            $table->text('pangkat_golongan28');
            $table->text('pangkat_golongan29');
            $table->text('pangkat_golongan30');
            $table->text('jabatan15');
            $table->text('jabatan16');
            $table->text('jabatan17');
            $table->text('jabatan18');
            $table->text('jabatan19');
            $table->text('jabatan20');
            $table->text('jabatan21');
            $table->text('jabatan22');
            $table->text('jabatan23');
            $table->text('jabatan27');
            $table->text('jabatan28');
            $table->text('jabatan29');
            $table->text('jabatan30');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        };
    }
    
    private function getLogSTSppdTableSchema()
    {
        return function ($table) {
            $table->id();
            $table->string('nomor_surat');
            $table->date('tanggal_surat');
            $table->string('perihal');
            $table->string('dasar_surat');
            $table->text('pelaksana');
            $table->string('tempat');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('pembuat_surat');
            $table->string('penandatangan');
            $table->string('file_surat')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        };
    }
    
    private function getLogBeritaPemindahanTableSchema()
    {
        return function ($table) {
            $table->id();
            $table->string('nomor_berita');
            $table->date('tanggal_berita');
            $table->string('dari_unit');
            $table->string('ke_unit');
            $table->text('deskripsi_arsip');
            $table->string('jumlah');
            $table->string('penerima');
            $table->string('penyerah');
            $table->string('saksi1');
            $table->string('saksi2');
            $table->string('file_berita')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        };
    }
    
    private function getLogBeritaPemusnahanTableSchema()
    {
        return function ($table) {
            $table->id();
            $table->string('nomor_berita');
            $table->date('tanggal_berita');
            $table->string('unit');
            $table->text('deskripsi_arsip');
            $table->string('jumlah');
            $table->string('alasan_pemusnahan');
            $table->string('metode_pemusnahan');
            $table->string('pelaksana');
            $table->string('saksi1');
            $table->string('saksi2');
            $table->string('file_berita')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        };
    }
    
    private function getLogBeritaAlihmediaTableSchema()
    {
        return function ($table) {
            $table->id();
            $table->string('nomor_berita');
            $table->date('tanggal_berita');
            $table->string('unit');
            $table->text('deskripsi_arsip');
            $table->string('jumlah');
            $table->string('media_asli');
            $table->string('media_tujuan');
            $table->string('alasan_alihmedia');
            $table->string('pelaksana');
            $table->string('saksi1');
            $table->string('saksi2');
            $table->string('file_berita')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        };
    }
    
    private function getNomorSpreadsheetTableSchema()
    {
        return function ($table) {
            $table->id();
            $table->string('jenis_nomor');
            $table->string('format_nomor');
            $table->integer('nomor_terakhir');
            $table->string('tahun');
            $table->string('keterangan')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        };
    }
    
    private function getSuratUndanganTableSchema()
    {
        return function ($table) {
            $table->id();
            $table->string('nomor_surat');
            $table->date('tanggal_surat');
            $table->string('perihal');
            $table->string('pengirim');
            $table->text('isi_surat');
            $table->string('tempat');
            $table->date('tanggal_acara');
            $table->time('waktu_acara');
            $table->string('file_surat')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        };
    }
    
    private function getNomorTemplateTableSchema()
    {
        return function ($table) {
            $table->id();
            $table->string('jenis_nomor');
            $table->string('format_nomor');
            $table->integer('nomor_terakhir');
            $table->string('tahun');
            $table->string('keterangan')->nullable();
            $table->string('file_template')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        };
    }
    
    private function getWatermarkLogTableSchema()
    {
        return function ($table) {
            $table->id();
            $table->string('jenis_watermark');
            $table->string('file_asli');
            $table->string('file_hasil');
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        };
    }
}