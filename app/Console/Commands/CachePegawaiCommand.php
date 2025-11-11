<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SuratTugasService;

class CachePegawaiCommand extends Command
{
    protected $signature = 'pegawai:cache';
    protected $description = 'Parse dan cache ulang data pegawai dari file Excel';

    public function handle(SuratTugasService $service)
    {
        $this->info('🔄 Memproses file pegawai...');
        $service->__construct(); // paksa reload
        $this->info('✅ Cache pegawai berhasil diperbarui.');
    }
}
