<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ArsipAktif;
use App\Models\ArsipInaktif;
use App\Models\ArsipVital;
use App\Models\ArsipAlihmedia;
use App\Models\LogSuratTugas;
use App\Models\LogStSppd;
use App\Models\LogBeritaPemindahan;
use App\Models\LogBeritaPemusnahan;
use App\Models\LogBeritaAlihmedia;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DashboardSeeder extends Seeder
{
    public function run()
    {
        // Get admin user
        $admin = User::where('username', 'admin')->first();
        if (!$admin) {
            $admin = User::create([
                'username' => 'admin',
                'email' => 'admin@sias.com',
                'password' => Hash::make('password'),
                'nama_lengkap' => 'Administrator SIAS',
                'jabatan' => 'Administrator',
                'role' => 'admin',
                'kode_petugas' => 'ADM001',
                'status' => 'aktif',
            ]);
        }

        // Hapus data yang ada untuk menghindari duplikat
        ArsipAktif::query()->delete();
        ArsipInaktif::query()->delete();
        ArsipVital::query()->delete();
        ArsipAlihmedia::query()->delete();
        LogSuratTugas::query()->delete();
        LogStSppd::query()->delete();
        LogBeritaPemindahan::query()->delete();
        LogBeritaPemusnahan::query()->delete();
        LogBeritaAlihmedia::query()->delete();

        // Create sample arsip aktif
        for ($i = 1; $i <= 10; $i++) {
            ArsipAktif::create([
                'kode_arsip' => 'AKT-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'judul_arsip' => 'Dokumen Aktif ' . $i,
                'kurun_waktu' => '2020-2024',
                'tingkat_perkembangan' => 'Asli',
                'jumlah' => rand(1, 10),
                'no_box' => 'B' . rand(1, 50),
                'no_rak' => 'R' . rand(1, 20),
                'lokasi_simpan' => 'Gudang Arsip A',
                'keterangan' => 'Dokumen penting unit ' . chr(65 + $i),
                'status' => 'active',
                'user_id' => $admin->id,
            ]);
        }

        // Create sample arsip inaktif
        for ($i = 1; $i <= 8; $i++) {
            ArsipInaktif::create([
                'kode_arsip' => 'INK-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'judul_arsip' => 'Dokumen Inaktif ' . $i,
                'kurun_waktu' => '2015-2019',
                'tingkat_perkembangan' => 'Copy',
                'jumlah' => rand(1, 5),
                'no_box' => 'B' . rand(51, 100),
                'no_rak' => 'R' . rand(21, 40),
                'lokasi_simpan' => 'Gudang Arsip B',
                'keterangan' => 'Dokumen lama unit ' . chr(65 + $i),
                'status' => 'active',
                'user_id' => $admin->id,
            ]);
        }

        // Create sample arsip vital
        for ($i = 1; $i <= 5; $i++) {
            ArsipVital::create([
                'kode_arsip' => 'VIT-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'judul_arsip' => 'Dokumen Vital ' . $i,
                'kurun_waktu' => '2020-2025',
                'tingkat_perkembangan' => 'Asli',
                'jumlah' => 1,
                'no_box' => 'BV' . $i,
                'no_rak' => 'RV' . $i,
                'lokasi_simpan' => 'Brankas Utama',
                'keterangan' => 'Dokumen sangat penting',
                'status' => 'active',
                'user_id' => $admin->id,
            ]);
        }

        // Create sample log surat tugas
        for ($i = 1; $i <= 7; $i++) {
            LogSuratTugas::create([
                'nomor_surat' => 'ST/' . date('Y') . '/' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'tanggal_surat' => Carbon::now()->subDays(rand(1, 30)),
                'perihal' => 'Penugasan ' . chr(65 + $i) . ' untuk kegiatan ' . ['pelatihan', 'audit', 'inspeksi', 'monitoring'][$i % 4],
                'dasar_surat' => 'Surat Direktur No. ' . rand(100, 999),
                'pelaksana' => 'Tim ' . chr(65 + $i),
                'tempat' => ['Ruang Rapat A', 'Lapangan', 'Kantor Cabang', 'Gudang'][$i % 4],
                'tanggal_mulai' => Carbon::now()->addDays(rand(1, 10)),
                'tanggal_selesai' => Carbon::now()->addDays(rand(11, 20)),
                'pembuat_surat' => 'Admin',
                'penandatangan' => 'Direktur',
                'user_id' => $admin->id,
            ]);
        }

        // Create sample log berita acara
        for ($i = 1; $i <= 5; $i++) {
            LogBeritaPemindahan::create([
                'nomor_berita' => 'BA.PIND/' . date('Y') . '/' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'tanggal_berita' => Carbon::now()->subDays(rand(1, 20)),
                'dari_unit' => 'Unit ' . chr(65 + $i),
                'ke_unit' => 'Unit ' . chr(66 + $i),
                'deskripsi_arsip' => 'Pemindahan dokumen ' . ['keuangan', 'SDM', 'operasional', 'legal', 'umum'][$i - 1],
                'jumlah' => rand(5, 20),
                'penerima' => 'Staff Penerima',
                'penyerah' => 'Staff Penyerah',
                'saksi1' => 'Saksi 1',
                'saksi2' => 'Saksi 2',
                'user_id' => $admin->id,
            ]);
        }
    }
}