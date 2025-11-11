<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    public function up()
    {
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
        
        // Import data arsip_aktif
        $this->importArsipAktif();
        
        // Import data log_surat_tugas
        $this->importLogSuratTugas();
        
        // Import data log_berita_pemindahan
        $this->importLogBeritaPemindahan();
        
        // Import data log_berita_pemusnahan
        $this->importLogBeritaPemusnahan();
        
        // Import data log_berita_alihmedia
        $this->importLogBeritaAlihmedia();
    }

    public function down()
    {
        // Kosongkan tabel yang sudah diimport
        DB::table('arsip_aktif')->truncate();
        DB::table('log_surat_tugas')->truncate();
        DB::table('log_berita_pemindahan')->truncate();
        DB::table('log_berita_pemusnahan')->truncate();
        DB::table('log_berita_alihmedia')->truncate();
    }

    private function importArsipAktif()
    {
        // Data dari arsip_aktif lama (tanpa ID agar auto-increment)
        $data = [
            [
                'nomor_arsip' => 'AT/AKTIF/2025/0001',
                'kode_ka' => 'TK.04.02',
                'berkas' => '1 BERKAS ',
                'uraian_isi' => 'Surat Tugas Mengikuti Studi Komparatif Pengembangan Unit Bisnis Rumah Sakit ke RS Mata Cicendo Bandung, Tanggal 14 Januari 2025. Dengan Nomor Surat TK.04.04/D.XXVI.1/945/2025. Atas Nama Ropingah Aprilia, S.Kep.Ns dan Kawan - Kawan',
                'tanggal' => '0000-00-00',
                'lokasi_simpan' => 'Tata Usaha',
                'file' => '',
                'status' => 'active',
                'deleted_at' => null,
                'user_id' => 1,
                'created_at' => '2025-10-03 04:43:14',
                'updated_at' => now(),
            ],
            [
                'nomor_arsip' => 'AT/AKTIF/2025/0002',
                'kode_ka' => 'KO.03.02',
                'berkas' => '1 BERAS ',
                'uraian_isi' => 'Surat Tugas Mengikuti Kegiatan Tournament Tenis Meja Dies Natalis FK-KMK UGM ke-79 di Gedung KPTU FKKMK UGM, Tanggal 30 Januari 2025. Dengan Nomor Surat KO.03.02/D.XXVI/2029/2025. Atas Nama Tri Hanggoro Kurniawan, A.Md dan Kawan-Kawan.',
                'tanggal' => '0000-00-00',
                'lokasi_simpan' => 'Tata Usaha',
                'file' => '',
                'status' => 'active',
                'deleted_at' => null,
                'user_id' => 1,
                'created_at' => '2025-10-03 04:50:23',
                'updated_at' => now(),
            ],
            // ... tambahkan data lainnya
        ];

        // Konversi ke format baru
        foreach ($data as $item) {
            $newData = [
                'kode_arsip' => $item['nomor_arsip'],
                'judul_arsip' => $item['uraian_isi'],
                'kurun_waktu' => $this->extractKurunWaktu($item['uraian_isi']),
                'tingkat_perkembangan' => 'Asli',
                'jumlah' => $this->extractJumlah($item['berkas']),
                'no_box' => $this->generateNoBox(),
                'no_rak' => $this->generateNoRak(),
                'lokasi_simpan' => $item['lokasi_simpan'],
                'keterangan' => 'Kode KA: ' . $item['kode_ka'],
                'status' => $item['status'],
                'user_id' => $item['user_id'],
                'created_at' => $item['created_at'],
                'updated_at' => $item['updated_at'],
            ];

            DB::table('arsip_aktif')->insert($newData);
        }
    }

    private function importLogSuratTugas()
    {
        // Data dari log_surat_tugas lama (tanpa ID agar auto-increment)
        $data = [
            [
                'nomor_surat' => 'ST/2025/001',
                'tanggal_surat' => '2025-01-14',
                'perihal' => 'Studi Komparatif Pengembangan Unit Bisnis Rumah Sakit',
                'dasar_surat' => 'TK.04.04/D.XXVI.1/945/2025',
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
    }

    private function importLogBeritaPemindahan()
    {
        // Data dari log_berita_pemindahan lama (tanpa ID agar auto-increment)
        $data = [
            [
                'nomor_berita' => 'BA.PIND/2025/001',
                'tanggal_berita' => '2025-01-15',
                'dari_unit' => 'Unit A',
                'ke_unit' => 'Unit B',
                'deskripsi_arsip' => 'Pemindahan dokumen keuangan',
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
    }

    private function importLogBeritaPemusnahan()
    {
        // Data dari log_berita_pemusnahan lama (tanpa ID agar auto-increment)
        $data = [
            [
                'nomor_berita' => 'BA.MUSNAH/2025/001',
                'tanggal_berita' => '2025-01-16',
                'unit' => 'Unit C',
                'deskripsi_arsip' => 'Pemusnahan dokumen lama',
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
    }

    private function importLogBeritaAlihmedia()
    {
        // Data dari log_berita_alihmedia lama (tanpa ID agar auto-increment)
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
    }

    // Helper functions
    private function extractKurunWaktu($uraian)
    {
        // Extract tahun dari uraian
        if (preg_match('/(\d{4})/', $uraian, $matches)) {
            return $matches[1];
        }
        return '2020-2024'; // Default
    }

    private function extractJumlah($berkas)
    {
        // Extract jumlah dari berkas
        if (preg_match('/(\d+)/', $berkas, $matches)) {
            return $matches[1];
        }
        return 1; // Default
    }

    private function generateNoBox()
    {
        return 'B' . rand(1, 100);
    }

    private function generateNoRak()
    {
        return 'R' . rand(1, 50);
    }
};