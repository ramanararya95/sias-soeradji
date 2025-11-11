<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LegacyDataSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Memulai import data lengkap...');
        
        // Kosongkan tabel terlebih dahulu
        DB::table('arsip_aktif')->truncate();
        DB::table('arsip_inaktif')->truncate();
        DB::table('arsip_vital')->truncate();
        DB::table('arsip_alihmedia')->truncate();
        DB::table('log_surat_tugas')->truncate();
        DB::table('log_st_sppd')->truncate();
        DB::table('log_berita_pemindahan')->truncate();
        DB::table('log_berita_pemusnahan')->truncate();
        DB::table('log_berita_alihmedia')->truncate();
        
        // Import semua data
        $this->importArsipAktif();
        $this->importArsipInaktif();
        $this->importArsipVital();
        $this->importArsipAlihmedia();
        $this->importLogSuratTugas();
        $this->importLogStSppd();
        $this->importLogBeritaPemindahan();
        $this->importLogBeritaPemusnahan();
        $this->importLogBeritaAlihmedia();
        
        $this->command->info('Import data lengkap selesai!');
    }

    private function importArsipAktif()
    {
        $data = [
            [
                'kode_arsip' => 'AT/AKTIF/2025/0001',
                'judul_arsip' => 'Surat Tugas Mengikuti Studi Komparatif Pengembangan Unit Bisnis Rumah Sakit ke RS Mata Cicendo Bandung, Tanggal 14 Januari 2025. Dengan Nomor Surat TK.04.04/D.XXVI.1/945/2025. Atas Nama Ropingah Aprilia, S.Kep.Ns dan Kawan - Kawan',
                'kurun_waktu' => '2025',
                'tingkat_perkembangan' => 'Asli',
                'jumlah' => 1,
                'no_box' => 'B29',
                'no_rak' => 'R1',
                'lokasi_simpan' => 'Tata Usaha',
                'keterangan' => 'Kode KA: TK.04.02',
                'status' => 'active',
                'user_id' => 1,
                'created_at' => '2025-10-03 04:43:14',
                'updated_at' => now(),
            ],
            [
                'kode_arsip' => 'AT/AKTIF/2025/0002',
                'judul_arsip' => 'Surat Tugas Mengikuti Kegiatan Tournament Tenis Meja Dies Natalis FK-KMK UGM ke-79 di Gedung KPTU FKKMK UGM, Tanggal 30 Januari 2025. Dengan Nomor Surat KO.03.02/D.XXVI/2029/2025. Atas Nama Tri Hanggoro Kurniawan, A.Md dan Kawan-Kawan.',
                'kurun_waktu' => '2025',
                'tingkat_perkembangan' => 'Asli',
                'jumlah' => 1,
                'no_box' => 'B30',
                'no_rak' => 'R2',
                'lokasi_simpan' => 'Tata Usaha',
                'keterangan' => 'Kode KA: KO.03.02',
                'status' => 'active',
                'user_id' => 1,
                'created_at' => '2025-10-03 04:50:23',
                'updated_at' => now(),
            ],
            // ... tambahkan semua data dari file SQL Anda
        ];

        foreach (array_chunk($data, 100) as $chunk) {
            DB::table('arsip_aktif')->insert($chunk);
        }
        
        $this->command->info(count($data) . ' data arsip_aktif diimport!');
    }

    private function importArsipInaktif()
    {
        $data = [
            [
                'kode_arsip' => 'INK/2025/0001',
                'judul_arsip' => 'Dokumen Inaktif 1',
                'kurun_waktu' => '2020-2024',
                'tingkat_perkembangan' => 'Copy',
                'jumlah' => 1,
                'no_box' => 'B101',
                'no_rak' => 'R10',
                'lokasi_simpan' => 'Gudang Arsip B',
                'keterangan' => 'Dokumen lama',
                'status' => 'active',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // ... tambahkan data lainnya
        ];

        foreach (array_chunk($data, 100) as $chunk) {
            DB::table('arsip_inaktif')->insert($chunk);
        }
        
        $this->command->info(count($data) . ' data arsip_inaktif diimport!');
    }

    private function importArsipVital()
    {
        $data = [
            [
                'kode_arsip' => 'VIT/2025/0001',
                'judul_arsip' => 'Dokumen Vital 1',
                'kurun_waktu' => '2020-2025',
                'tingkat_perkembangan' => 'Asli',
                'jumlah' => 1,
                'no_box' => 'BV1',
                'no_rak' => 'RV1',
                'lokasi_simpan' => 'Brankas Utama',
                'keterangan' => 'Dokumen sangat penting',
                'status' => 'active',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // ... tambahkan data lainnya
        ];

        foreach (array_chunk($data, 100) as $chunk) {
            DB::table('arsip_vital')->insert($chunk);
        }
        
        $this->command->info(count($data) . ' data arsip_vital diimport!');
    }

    private function importArsipAlihmedia()
    {
        $data = [
            [
                'kode_arsip' => 'ALIH/2025/0001',
                'judul_arsip' => 'Dokumen Alih Media 1',
                'kurun_waktu' => '2020-2025',
                'tingkat_perkembangan' => 'Asli',
                'jumlah' => 1,
                'no_box' => 'BA1',
                'no_rak' => 'RA1',
                'lokasi_simpan' => 'Gudang Digital',
                'media_asli' => 'Kertas',
                'media_tujuan' => 'Digital',
                'keterangan' => 'Dokumen yang dialih medianya',
                'status' => 'active',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // ... tambahkan data lainnya
        ];

        foreach (array_chunk($data, 100) as $chunk) {
            DB::table('arsip_alihmedia')->insert($chunk);
        }
        
        $this->command->info(count($data) . ' data arsip_alihmedia diimport!');
    }

    private function importLogSuratTugas()
    {
        $data = [
            [
                'nomor_surat' => 'TK.04.04/D.XXVI.1/945/2025',
                'tanggal_surat' => '2025-01-14',
                'perihal' => 'Studi Komparatif Pengembangan Unit Bisnis Rumah Sakit',
                'dasar_surat' => 'Permintaan Direktur',
                'pelaksana' => 'Ropingah Aprilia, S.Kep.Ns dan Kawan-kawan',
                'tempat' => 'RS Mata Cicendo Bandung',
                'tanggal_mulai' => '2025-01-14',
                'tanggal_selesai' => '2025-01-14',
                'pembuat_surat' => 'Admin',
                'penandatangan' => 'Direktur',
                'file_surat' => null,
                'user_id' => 1,
                'created_at' => '2025-10-03 04:43:14',
                'updated_at' => now(),
            ],
            // ... tambahkan data lainnya
        ];

        foreach (array_chunk($data, 100) as $chunk) {
            DB::table('log_surat_tugas')->insert($chunk);
        }
        
        $this->command->info(count($data) . ' data log_surat_tugas diimport!');
    }

    private function importLogStSppd()
    {
        $data = [
            [
                'nomor_surat' => 'ST.SPPD/2025/001',
                'tanggal_surat' => '2025-01-15',
                'perihal' => 'Perjalanan Dinas',
                'dasar_surat' => 'Permintaan Direktur',
                'pelaksana' => 'Staff A',
                'tempat' => 'Jakarta',
                'tanggal_mulai' => '2025-01-20',
                'tanggal_selesai' => '2025-01-22',
                'kendaraan' => 'Mobil',
                'akomodasi' => 'Hotel',
                'pembuat_surat' => 'Admin',
                'penandatangan' => 'Direktur',
                'file_surat' => null,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // ... tambahkan data lainnya
        ];

        foreach (array_chunk($data, 100) as $chunk) {
            DB::table('log_st_sppd')->insert($chunk);
        }
        
        $this->command->info(count($data) . ' data log_st_sppd diimport!');
    }

    private function importLogBeritaPemindahan()
    {
        $data = [
            [
                'nomor_berita' => 'BA.PIND/2025/001',
                'tanggal_berita' => '2025-01-15',
                'dari_unit' => 'Unit A',
                'ke_unit' => 'Unit B',
                'deskripsi_arsip' => 'Pemindahan dokumen keuangan tahun 2024',
                'jumlah' => '10',
                'penerima' => 'Staff Penerima',
                'penyerah' => 'Staff Penyerah',
                'saksi1' => 'Saksi 1',
                'saksi2' => 'Saksi 2',
                'file_berita' => null,
                'user_id' => 1,
                'created_at' => '2025-10-03 05:00:00',
                'updated_at' => now(),
            ],
            // ... tambahkan data lainnya
        ];

        foreach (array_chunk($data, 100) as $chunk) {
            DB::table('log_berita_pemindahan')->insert($chunk);
        }
        
        $this->command->info(count($data) . ' data log_berita_pemindahan diimport!');
    }

    private function importLogBeritaPemusnahan()
    {
        $data = [
            [
                'nomor_berita' => 'BA.MUSNAH/2025/001',
                'tanggal_berita' => '2025-01-16',
                'unit' => 'Unit C',
                'deskripsi_arsip' => 'Pemusnahan dokumen kadaluarsa',
                'jumlah' => '5',
                'alasan_pemusnahan' => 'Kadaluarsa',
                'metode_pemusnahan' => 'Shredding',
                'pelaksana' => 'Staff Pelaksana',
                'saksi1' => 'Saksi 1',
                'saksi2' => 'Saksi 2',
                'file_berita' => null,
                'user_id' => 1,
                'created_at' => '2025-10-03 05:00:00',
                'updated_at' => now(),
            ],
            // ... tambahkan data lainnya
        ];

        foreach (array_chunk($data, 100) as $chunk) {
            DB::table('log_berita_pemusnahan')->insert($chunk);
        }
        
        $this->command->info(count($data) . ' data log_berita_pemusnahan diimport!');
    }

    private function importLogBeritaAlihmedia()
    {
        $data = [
            [
                'nomor_berita' => 'BA.ALIH/2025/001',
                'tanggal_berita' => '2025-01-17',
                'unit' => 'Unit D',
                'deskripsi_arsip' => 'Alih media dokumen vital',
                'jumlah' => '3',
                'media_asli' => 'Kertas',
                'media_tujuan' => 'Digital',
                'alasan_alihmedia' => 'Preservasi',
                'pelaksana' => 'Staff Pelaksana',
                'saksi1' => 'Saksi 1',
                'saksi2' => 'Saksi 2',
                'file_berita' => null,
                'user_id' => 1,
                'created_at' => '2025-10-03 05:00:00',
                'updated_at' => now(),
            ],
            // ... tambahkan data lainnya
        ];

        foreach (array_chunk($data, 100) as $chunk) {
            DB::table('log_berita_alihmedia')->insert($chunk);
        }
        
        $this->command->info(count($data) . ' data log_berita_alihmedia diimport!');
    }
}