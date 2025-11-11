<?php

// app/Models/BeritaAcaraPemusnahan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeritaAcaraPemusnahan extends Model
{
    use HasFactory;
    
    protected $table = 'berita_acara_pemusnahan';
    
    protected $fillable = [
        'user_id',
        'nomor',
        'tanggal',
        'lokasi',
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