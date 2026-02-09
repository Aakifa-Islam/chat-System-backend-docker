<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model; // Zaroori: MongoDB wala model use karein

class WorkspaceMember extends Model
{
   // protected $connection = 'mongodb';
    //protected $collection = 'workspace_members'; // Aapki collection ka naam

    protected $fillable = [
        'workspace_id',
        'user_id',
        'role'
    ];
}