<?php

// app/Models/BeritaAcaraAlihmedia.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeritaAcaraAlihmedia extends Model
{
    use HasFactory;
    
    protected $table = 'berita_acara_alihmedia';
    
    protected $fillable = [
        'user_id',
        'nomor',
        'tanggal',
        'media_asal',
        'media_tujuan',
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