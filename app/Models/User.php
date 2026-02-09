<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;

class User extends Model implements AuthenticatableContract 
{
    use Authenticatable, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'is_verified', 
        'verification_token', 'email_verified_at', 
        'status', 'reset_otp', 'otp_expiry'
    ];

    // Relation: User ke paas bahut saaray tokens ho saktay hain
    public function tokens() 
    {
        return $this->hasMany(Token::class);
    }

    // Static function for registration
    public static function add($data) 
    {
        return self::create([
            'name'        => $data['name'],
            'email'       => $data['email'],
            'password'    => Hash::make($data['password']),
            'is_verified' => false
        ]);
    }
}