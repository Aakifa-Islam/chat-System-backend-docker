<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource {
    public function toArray($request): array {
        return [
            'id'          => (string) $this->_id,
            'name'        => $this->name,
            'email'       => $this->email,
            'status'      => $this->status,
            'is_verified' => $this->is_verified
        ];
    }
}