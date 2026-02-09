<?php
namespace App\Http\Requests\Workspace;
use Illuminate\Foundation\Http\FormRequest;

class CreateWorkspaceRequest extends FormRequest {
    public function authorize() { return true; }
    public function rules() {
        return [
            'name' => 'required|string|unique:workspaces,name',
            'description' => 'nullable|string'
        ];
    }
}