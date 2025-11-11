<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'foto',
        'bio',
        'phone',
        'address',
        'birth_date',
        'place_of_birth',
        'gender',
        'education',
        'skill'
    ];
    
    protected $casts = [
        'birth_date' => 'date'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}