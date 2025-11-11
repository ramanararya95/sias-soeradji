<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanNomor extends Model
{
    use HasFactory;
    
    protected $fillable = ['panjang_nomor_urut'];
}