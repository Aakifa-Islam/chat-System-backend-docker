<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource {
    public function toArray($request) {
        return [
            'team_id'      => $this->_id,
            'team_name'    => $this->name,
            'workspace'    => $this->workspace->name ?? 'N/A',
            'created_at'   => $this->created_at->format('Y-m-d')
        ];
    }
}