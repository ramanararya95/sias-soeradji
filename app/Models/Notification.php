<?php

// app/Models/Notification.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'read_at',
        'type'
    ];
    
    protected $casts = [
        'read_at' => 'datetime',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Tambahkan scope untuk notifikasi yang belum dibaca
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }
}