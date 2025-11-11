<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipAlihmedia extends Model
{
    use HasFactory;
    
    protected $table = 'arsip_alihmedia';

    protected $fillable = [
        'nomor_arsip',
        'organisasi',
        'unit_pengolah',
        'jenis_arsip',
        'kurun_waktu',
        'media_semula',
        'media_menjadi',
        'jumlah',
        'alat',
        'waktu',
        'keterangan',
        'file',
        'user_id'
    ];
    
    protected $casts = [
        'waktu' => 'date'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}