<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings'; // nama tabel di database

    protected $fillable = [
        'user_id',
        'theme',
        'language',
    ];

    // Relasi ke user (opsional tapi disarankan)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
