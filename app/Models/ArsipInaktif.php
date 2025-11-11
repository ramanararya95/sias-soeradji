<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipInaktif extends Model
{
    use HasFactory;
    
   // 👇 Tambahkan baris ini agar tidak dicari ke arsip_inaktifs
    protected $table = 'arsip_inaktif';

    protected $fillable = [
        'nomor_arsip',
        'kode_ka',
        'uraian_isi',
        'tahun',
        'volume',
        'keterangan',
        'file',
        'user_id'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}