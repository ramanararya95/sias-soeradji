<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratTugas extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log_surat_tugas'; // Sesuaikan dengan nama tabel Anda

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_gelar1',
        'nama_gelar2',
        'nama_gelar3', // Tambahkan jika ada
        'nama_gelar4', // Tambahkan jika ada
        'nama_gelar5',
        'nama_gelar6',
        'nama_gelar7',
        'nama_gelar8',
        'nama_gelar9',
        'nama_gelar10',
        'nama_gelar11',
        'nama_gelar12',
        'nama_gelar13',
        'nama_gelar14',
        'nama_gelar14',
        'nama_gelar15',
        'nama_gelar16',
        'nama_gelar17',
        'nama_gelar18',
        'nama_gelar19',
        'nama_gelar20',
        'nama_gelar21',
        'nama_gelar22',
        'nama_gelar23',
        'nama_gelar24',
        'nama_gelar25',
        'nama_gelar26',
        'nama_gelar27',
        'nama_gelar28',
        'nama_gelar29',
        'nama_gelar30',
        'hal',
        'filename_word',
        'file_size',
        'template_type',
        'created_at',
        'updated_at'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}