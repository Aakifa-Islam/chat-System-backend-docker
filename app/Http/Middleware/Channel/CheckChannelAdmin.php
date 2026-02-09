<?php

namespace App\Http\Middleware\Channel;

use Closure;
use App\Models\Channel;
use Illuminate\Http\Request;

class CheckChannelAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $channelId = $request->route('id');
        $channel = Channel::find($channelId);

        if (!$channel) {
            return response()->json(['status' => 'error', 'message' => 'Channel not found'], 404);
        }

        // Authority Check: Sirf admin hi update/delete kar sakta hai
        if ($channel->admin_id !== auth()->id()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized: Only channel admin can perform this action'], 403);
        }

        // Suthra kaam: Channel instance ko request mein daal dein
        $request->merge(['channel_instance' => $channel]);

        return $next($request);
    }
}