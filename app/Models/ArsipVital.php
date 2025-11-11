<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipVital extends Model
{
    use HasFactory;
    
    protected $table = 'arsip_vital';

    protected $fillable = [
        'nomor_arsip',
        'nama_instansi',
        'jenis_arsip',
        'unit_kerja',
        'kurun_waktu',
        'media',
        'jumlah',
        'jangka_simpan',
        'lokasi_simpan',
        'metode_perlindungan',
        'keterangan',
        'user_id'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}