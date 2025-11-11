<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ImportAllData extends Command
{
    protected $signature = 'import:all';
    protected $description = 'Import semua data dari file SQL lama';

    public function handle()
    {
        $this->info('Memulai import semua data...');
        
        // Kosongkan tabel
        $this->truncateTables();
        
        // Import data
        $this->importArsipAktif();
        $this->importArsipInaktif();
        $this->importArsipVital();
        $this->importArsipAlihmedia();
        $this->importLogSuratTugas();
        $this->importLogStSppd();
        $this->importLogBeritaPemindahan();
        $this->importLogBeritaPemusnahan();
        $this->importLogBeritaAlihmedia();
        
        $this->info('Import semua data selesai!');
        return Command::SUCCESS;
    }

    private function truncateTables()
    {
        $tables = [
            'arsip_aktif', 'arsip_inaktif', 'arsip_vital', 'arsip_alihmedia',
            'log_surat_tugas', 'log_st_sppd', 
            'log_berita_pemindahan', 'log_berita_pemusnahan', 'log_berita_alihmedia'
        ];
        
        foreach ($tables as $table) {
            DB::table($table)->truncate();
            $this->info("Tabel {$table} dikosongkan");
        }
    }

    private function importArsipAktif()
    {
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
            // ... tambahkan semua 130 data dari file SQL Anda
        ];

        foreach (array_chunk($data, 50) as $chunk) {
            DB::table('arsip_aktif')->insert($chunk);
        }
        
        $this->info(count($data) . ' data arsip_aktif diimport!');
    }

    // ... tambahkan method lainnya untuk setiap tabel
}