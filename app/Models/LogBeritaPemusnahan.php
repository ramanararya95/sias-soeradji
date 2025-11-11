<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogBeritaPemusnahan extends Model
{
    use HasFactory;

    protected $table = 'log_berita_pemusnahan'; // Tambah ini

    protected $fillable = [
        'nomor_berita',
        'tanggal_berita',
        'unit',
        'deskripsi_arsip',
        'jumlah',
        'alasan_pemusnahan',
        'metode_pemusnahan',
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