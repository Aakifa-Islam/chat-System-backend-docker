<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource {
  public function toArray($request): array
{
    return [
        'id'         => $this->_id,
        'sender_id'  => $this->sender_id,
        'content'    => $this->content,
        'type'       => $this->type,
        'file_name'  => $this->file_name,
        // File view karne ka suthra link
        'file_url'   => $this->file_id ? url("/api/message/file/{$this->_id}") : null,
        'created_at' => $this->created_at->format('Y-m-d H:i:s'),
    ];
}
}