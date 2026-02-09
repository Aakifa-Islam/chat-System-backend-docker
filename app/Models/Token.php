<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Token extends Model
{
    // MongoDB collection ka naam specify kar dein
    protected $collection = 'tokens'; 

    protected $fillable = [
        'user_id', 
        'token', 
        'expires_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}