<?php

// app/Models/BeritaAcaraPemindahan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeritaAcaraPemindahan extends Model
{
    use HasFactory;
    
    protected $table = 'berita_acara_pemindahan';
    
    protected $fillable = [
        'user_id',
        'nomor',
        'tanggal',
        'lokasi_asal',
        'lokasi_tujuan',
        'keterangan',
        'status',
    ];
    
    protected $casts = [
        'tanggal' => 'date',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}