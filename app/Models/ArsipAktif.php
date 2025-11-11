<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipAktif extends Model
{
    use HasFactory;

    protected $table = 'arsip_aktif'; // 👈 nama tabel sesuai di database kamu
    
    protected $fillable = [
        'nomor_arsip',
        'kode_ka',
        'uraian_isi',
        'berkas',
        'tanggal',
        'lokasi_simpan',
        'file',
        'user_id'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}