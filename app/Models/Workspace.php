<?php
namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class Workspace extends Model {
    protected $fillable = ['name', 'description', 'owner_id', 'invite_code', 'member_ids'];

    // // Cast member_ids as array for MongoDB
    // protected $casts = [
    //     'member_ids' => 'array'
    // ];

    public function owner() {
        return $this->belongsTo(User::class, 'owner_id');
    }
}