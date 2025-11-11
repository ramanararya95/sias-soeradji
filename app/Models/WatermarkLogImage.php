<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WatermarkLogImage extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'original_filename',
        'watermarked_filename',
        'file_size',
        'file_type',
        'file_path',
        'user_id'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}