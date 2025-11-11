<?php

// app/Models/Activity.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'description',
        'subject_type',
        'subject_id',
        'created_at',
        'updated_at'
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function subject()
    {
        return $this->morphTo();
    }
}