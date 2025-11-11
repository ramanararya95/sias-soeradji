<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PengaturanNomor;

class PengaturanNomorSeeder extends Seeder
{
    public function run()
    {
        PengaturanNomor::create([
            'panjang_nomor_urut' => 4
        ]);
    }
}