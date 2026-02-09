<?php
namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class Team extends Model {
    protected $fillable = ['name', 'description', 'workspace_id'];

    // Relation: Ek team ke andar bohot saaray channels ho saktay hain
    public function channels() {
        return $this->hasMany(Channel::class, 'team_id');
    }

    public function workspace() {
        return $this->belongsTo(Workspace::class);
    }
}