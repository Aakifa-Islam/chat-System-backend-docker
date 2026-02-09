<?php
namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class Channel extends Model {
   protected $fillable = ['name', 'type', 'team_id', 'workspace_id', 'admin_id', 'member_ids'];

   // protected $casts = ['member_ids' => 'array'];

    // Relation back to Team
    public function team() {
        return $this->belongsTo(Team::class, 'team_id');
    }
}