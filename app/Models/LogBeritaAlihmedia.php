<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogBeritaAlihmedia extends Model
{
    use HasFactory;

    protected $table = 'log_berita_alihmedia'; // Tambah ini

    protected $fillable = [
        'nomor_berita',
        'tanggal_berita',
        'unit',
        'deskripsi_arsip',
        'jumlah',
        'media_asli',
        'media_tujuan',
        'alasan_alihmedia',
        'pelaksana',
        'saksi1',
        'saksi2',
        'file_berita',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}