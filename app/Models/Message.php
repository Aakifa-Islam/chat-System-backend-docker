<?php
namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class Message extends Model {
    protected $fillable = [
        'sender_id', 
        'channel_id', 
        'content',      // Text message
        'file_id',       // GridFS File ID
        'file_name', 
        'type'          // 'text' or 'attachment'
    ];

    public function sender() { return $this->belongsTo(User::class, 'sender_id'); }
    public function channel() { return $this->belongsTo(Channel::class); }
}