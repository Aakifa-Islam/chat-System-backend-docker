<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkspaceResource extends JsonResource {
    public function toArray($request) {
        return [
            'id'           => $this->_id,
            'name'         => $this->name,
            'invite_code'  => $this->invite_code, // Code frontend ko dikhayenge
            'is_admin'     => $this->owner_id === auth()->id(),
            'members_count'=> count($this->member_ids ?? []),
            'created_at'   => $this->created_at->format('Y-m-d')
        ];
    }
}