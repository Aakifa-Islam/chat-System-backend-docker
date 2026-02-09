public function handle($request, Closure $next)
{
    $message = \App\Models\Message::findOrFail($request->route('id'));

    if (!$message->file_id) {
        return response()->json(['message' => 'This message has no attachment'], 404);
    }

    // Channel membership check
    $channel = \App\Models\Channel::find($message->channel_id);
    if (!in_array(auth()->id(), $channel->member_ids ?? [])) {
        return response()->json(['message' => 'Unauthorized access to file'], 403);
    }

    // Message instance ko request mein daal dein
    $request->merge(['message_file' => $message]);

    return $next($request);
}