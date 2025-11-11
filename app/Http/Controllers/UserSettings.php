<?php

namespace App\Models\Controllers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSettings extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'theme',
        'email_notifications',
        'chat_notifications',
        'language'
    ];
    
    protected $casts = [
        'email_notifications' => 'boolean',
        'chat_notifications' => 'boolean'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}