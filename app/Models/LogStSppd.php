<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogStSppd extends Model
{
    use HasFactory;

    protected $table = 'log_st_sppd'; // Tambah ini

    protected $fillable = [
        'nomor_surat',
        'tanggal_surat',
        'perihal',
        'dasar_surat',
        'pelaksana',
        'tempat',
        'tanggal_mulai',
        'tanggal_selesai',
        'kendaraan',
        'akomodasi',
        'pembuat_surat',
        'penandatangan',
        'file_surat',
        'user_id',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}