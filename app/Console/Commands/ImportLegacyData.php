<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ImportLegacyData extends Command
{
    protected $signature = 'import:legacy';
    protected $description = 'Import data dari database PHP lama ke Laravel';

    public function handle()
    {
        $this->info('Memulai import data legacy...');

        // Import arsip_aktif
        $this->importArsipAktif();
        
        // Import log_surat_tugas
        $this->importLogSuratTugas();
        
        // Import log_berita_pemindahan
        $this->importLogBeritaPemindahan();
        
        // Import log_berita_pemusnahan
        $this->importLogBeritaPemusnahan();
        
        // Import log_berita_alihmedia
        $this->importLogBeritaAlihmedia();

        $this->info('Import data legacy selesai!');
        return Command::SUCCESS;
    }

    private function importArsipAktif()
    {
        $this->info('Import data arsip_aktif...');
        
        // Data lengkap dari file SQL Anda
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
            [
                'kode_arsip' => 'AT/AKTIF/2025/0003',
                'judul_arsip' => 'Surat Tugas Mengikuti Pelatihan Penatalaksanaan Pasien Kanker dengan Kemoterapi Bagi Perawat di Gedung Diklat RSUP Dr. Kariadi, Tanggal 23 Januari 2025. Dengan Nomor Surat PL.02.01/D.XXVI/1670/2025. Atas Nama Dyan Kurniawati Maria Regina dan Kawan-Kawan.',
                'kurun_waktu' => '2025',
                'tingkat_perkembangan' => 'Asli',
                'jumlah' => 1,
                'no_box' => 'B31',
                'no_rak' => 'R3',
                'lokasi_simpan' => 'Tata Usaha',
                'keterangan' => 'Kode KA: PL.02.01',
                'status' => 'active',
                'user_id' => 1,
                'created_at' => '2025-10-03 04:56:01',
                'updated_at' => now(),
            ],
            [
                'kode_arsip' => 'AT/AKTIF/2025/0004',
                'judul_arsip' => 'Surat Tugas Rencana Kegiatan Reviu Laporan Keuangan Semester II Tahun 2024, Tanggal 20 Januari 2025. Dengan Nomor Surat PS.02.01/D.XXVI/501/2025. Atas Nama drg.Retno Dyah Parwitasari,MMR dan Kawan-Kawan.',
                'kurun_waktu' => '2025',
                'tingkat_perkembangan' => 'Asli',
                'jumlah' => 1,
                'no_box' => 'B32',
                'no_rak' => 'R4',
                'lokasi_simpan' => 'Tata Usaha',
                'keterangan' => 'Kode KA: PS.02.01',
                'status' => 'active',
                'user_id' => 1,
                'created_at' => '2025-10-03 05:21:11',
                'updated_at' => now(),
            ],
            [
                'kode_arsip' => 'AT/AKTIF/2025/0005',
                'judul_arsip' => 'Surat Tugas Menghadiri Acara Pemaparan Kegiatan Pembangunan Gedung Onkologi RS Soeradji Tirtonegoro dalam TA 2025 di Ruang Rapat Asisten Inteligen Kejati Jateng Semarang, Tanggal 13 Januari 2025. Dengan Nomor Surat KP.05.06/D.XXVI/728/2024. Atas Nama Widiatmo Wibowo,SE,MBA dan Kawan-Kawan',
                'kurun_waktu' => '2025',
                'tingkat_perkembangan' => 'Asli',
                'jumlah' => 1,
                'no_box' => 'B33',
                'no_rak' => 'R5',
                'lokasi_simpan' => 'Tata Usaha',
                'keterangan' => 'Kode KA: KP.05.06',
                'status' => 'active',
                'user_id' => 1,
                'created_at' => '2025-10-03 06:19:26',
                'updated_at' => now(),
            ],
            [
                'kode_arsip' => 'FN/AKTIF/2025/0001',
                'judul_arsip' => 'Surat Tugas Melaksanakan Kegiatan Studi Banding Strategi Pemasaran dan Digital Marketing ke RS Vertikal Kementrian Kesehatan Surabaya, Tanggal: 25 Februari 2025, NO: PK.04.01/D.XXVI/4124/2025, atas nama dr.Muslikhah Yuni Farkhati, M.Sc,Sp.A (K) dan Kawan-kawan.',
                'kurun_waktu' => '2025',
                'tingkat_perkembangan' => 'Asli',
                'jumlah' => 1,
                'no_box' => 'B34',
                'no_rak' => 'R6',
                'lokasi_simpan' => 'Tata Usaha',
                'keterangan' => 'Kode KA: PK.04.01',
                'status' => 'active',
                'user_id' => 1,
                'created_at' => '2025-10-03 06:22:00',
                'updated_at' => now(),
            ],
            // ... tambahkan semua data lainnya dari file SQL Anda
        ];

        foreach ($data as $item) {
            DB::table('arsip_aktif')->insert($item);
        }
        
        $this->info(count($data) . ' data arsip_aktif berhasil diimport!');
    }

    private function importLogSuratTugas()
    {
        $this->info('Import data log_surat_tugas...');
        
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

        foreach ($data as $item) {
            DB::table('log_surat_tugas')->insert($item);
        }
        
        $this->info(count($data) . ' data log_surat_tugas berhasil diimport!');
    }

    private function importLogBeritaPemindahan()
    {
        $this->info('Import data log_berita_pemindahan...');
        
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

        foreach ($data as $item) {
            DB::table('log_berita_pemindahan')->insert($item);
        }
        
        $this->info(count($data) . ' data log_berita_pemindahan berhasil diimport!');
    }

    private function importLogBeritaPemusnahan()
    {
        $this->info('Import data log_berita_pemusnahan...');
        
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

        foreach ($data as $item) {
            DB::table('log_berita_pemusnahan')->insert($item);
        }
        
        $this->info(count($data) . ' data log_berita_pemusnahan berhasil diimport!');
    }

    private function importLogBeritaAlihmedia()
    {
        $this->info('Import data log_berita_alihmedia...');
        
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

        foreach ($data as $item) {
            DB::table('log_berita_alihmedia')->insert($item);
        }
        
        $this->info(count($data) . ' data log_berita_alihmedia berhasil diimport!');
    }
}