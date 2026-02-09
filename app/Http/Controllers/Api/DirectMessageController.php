<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Http\Requests\Message\SendDirectMessageRequest;
use App\Http\Resources\DirectMessageResource;
use Illuminate\Http\JsonResponse;

class DirectMessageController extends Controller {
    
    // Message Bhejna
    public function create(SendDirectMessageRequest $request): JsonResponse {
        $message = Message::create([
            'sender_id'    => auth()->id(),
            'receiver_id'  => $request->receiver_id,
            'workspace_id' => $request->workspace_id,
            'content'      => $request->content,
            'file_id'      => $request->grid_file_id ?? null,
            'file_name'    => $request->original_name ?? null,
            'type'         => $request->grid_file_id ? 'attachment' : 'text',
        ]);

        return response()->json(['status' => 'success', 'data' => new DirectMessageResource($message)]);
    }

    // Ali aur Ahmad ki conversation fetch karna
  public function readAll($receiver_id): JsonResponse {
    $senderId = auth()->id();

    // Query ko check karein ke Ali ne Ahmad ko bheja ho YA Ahmad ne Ali ko
    $messages = Message::where(function($q) use ($senderId, $receiver_id) {
        $q->where('sender_id', (string)$senderId)
          ->where('receiver_id', (string)$receiver_id);
    })
    ->orWhere(function($q) use ($senderId, $receiver_id) {
        $q->where('sender_id', (string)$receiver_id)
          ->where('receiver_id', (string)$senderId);
    })
    ->orderBy('created_at', 'asc')
    ->get();

    return response()->json([
        'status' => 'success', 
        'count'  => $messages->count(), // Ye check karne ke liye ke kitne mile
        'data'   => DirectMessageResource::collection($messages)
    ]);
}
}