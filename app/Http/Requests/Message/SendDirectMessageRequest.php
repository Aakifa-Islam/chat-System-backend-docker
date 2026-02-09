<?php
namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;

class SendDirectMessageRequest extends FormRequest {
    public function rules() {
        return [
            'receiver_id'  => 'required|string',
            'workspace_id' => 'required|string',
            'content'      => 'required_without:attachment|string|nullable',
            'attachment'   => 'nullable|file|max:10240', // 10MB limit
        ];
    }
}