<?php

namespace App\Http\Requests\Team;

use Illuminate\Foundation\Http\FormRequest;

class CreateTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Middleware handle kar raha hai authentication
    }

    public function rules(): array
    {
        return [
            'name'         => 'required|string|max:100',
            'description'  => 'nullable|string|max:255',
            'workspace_id' => 'required|exists:workspaces,_id', // MongoDB ki _id check karega
        ];
    }

    public function messages(): array
    {
        return [
            'workspace_id.exists' => 'The selected workspace does not exist.',
            'name.required'       => 'A team name is required.',
        ];
    }
}