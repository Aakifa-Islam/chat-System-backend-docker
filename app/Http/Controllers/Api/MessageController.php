<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Http\Requests\Message\SendMessageRequest;
use App\Http\Requests\Message\UpdateMessageRequest;
use App\Http\Resources\MessageResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller {
    
    public function create(SendMessageRequest $request): JsonResponse {
        $message = Message::create([
            'sender_id'  => auth()->id(),
            'channel_id' => $request->channel_id,
            'content'    => $request->content,
            'file_id'    => $request->grid_file_id ?? null,
            'file_name'  => $request->original_name ?? null,
            'type'       => $request->grid_file_id ? 'attachment' : 'text',
        ]);

        return response()->json(['status' => 'success', 'data' => new MessageResource($message)]);
    }

    public function readAll($channel_id): JsonResponse {
        $messages = Message::where('channel_id', $channel_id)->latest()->get();
        return response()->json(['status' => 'success', 'data' => MessageResource::collection($messages)]);
    }

    public function readFile($id) {
        // File instance middleware (check.file.access) se aa raha hai
        $message = Message::findOrFail($id); 
        $bucket = DB::connection('mongodb')->getMongoDB()->selectGridFSBucket();
        $stream = $bucket->openDownloadStream($message->file_id);

        return response()->stream(function() use ($stream) {
            fpassthru($stream);
        }, 200, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="'.$message->file_name.'"'
        ]);
    }

    public function update(UpdateMessageRequest $request, $id): JsonResponse {
        $message = $request->message_instance;
        $message->update($request->validated());
        return response()->json(['status' => 'success', 'data' => new MessageResource($message)]);
    }

    public function delete($id): JsonResponse {
        $message = \App\Models\Message::findOrFail($id);
        $message->delete();
        return response()->json(['status' => 'success', 'message' => 'Message deleted']);
    }
}