<?php
namespace App\Http\Middleware\Message;
use Closure;
use App\Models\Message;

class CheckMessageOwnership {
    public function handle($request, Closure $next) {
        $message = Message::find($request->route('id'));

        if (!$message) {
            return response()->json(['message' => 'Message not found'], 404);
        }

        if ($message->sender_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized: You did not send this message'], 403);
        }

        $request->merge(['message_instance' => $message]);
        return $next($request);
    }
}