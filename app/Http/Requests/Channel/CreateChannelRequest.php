<?php

namespace App\Http\Requests\Channel;

use Illuminate\Foundation\Http\FormRequest;

class CreateChannelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => 'required|string|max:100',
            'type'         => 'required|in:public,private',
            'team_id'      => 'required|exists:teams,_id', // MongoDB id check
            'workspace_id' => 'required|exists:workspaces,_id'
        ];
    }
}