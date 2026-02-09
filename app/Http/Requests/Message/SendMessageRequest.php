<?php
namespace App\Http\Requests\Message;
use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest {
    public function authorize() { return true; }

    public function rules() {
        return [
            'channel_id' => 'required|exists:channels,_id',
            'content'    => 'required_without:attachment|string',
            'attachment' => 'required_without:content|file|max:10240', // 10MB limit
        ];
    }
}