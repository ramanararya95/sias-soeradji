<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogBeritaPemindahan extends Model
{
    use HasFactory;

    protected $table = 'log_berita_pemindahan'; // Tambah ini

    protected $fillable = [
        'nomor_berita',
        'tanggal_berita',
        'dari_unit',
        'ke_unit',
        'deskripsi_arsip',
        'jumlah',
        'penerima',
        'penyerah',
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