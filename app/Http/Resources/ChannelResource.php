<?php

namespace App\Http\Resources; // Namespace bilkul yahi hona chahiye

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChannelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->_id,
            'name'          => $this->name,
            'type'          => $this->type,
            'team_id'       => $this->team_id,
            'workspace_id'  => $this->workspace_id,
            'is_admin'      => $this->admin_id === auth()->id(),
            'members_count' => count($this->member_ids ?? [])
        ];
    }
}