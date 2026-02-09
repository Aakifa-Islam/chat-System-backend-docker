<?php

namespace App\Http\Middleware\Channel;

use Closure;
use App\Models\Channel;
use Illuminate\Http\Request;

class CheckChannelLeave
{
    public function handle(Request $request, Closure $next)
    {
        $channelId = $request->route('id');
        $channel = Channel::find($channelId);

        if (!$channel) {
            return response()->json(['status' => 'error', 'message' => 'Channel not found'], 404);
        }

        // Check 1: Kya user member hai bhi? (Jo member nahi wo leave kaise karega)
        if (!in_array(auth()->id(), $channel->member_ids ?? [])) {
            return response()->json(['status' => 'error', 'message' => 'You are not a member of this channel'], 400);
        }

        // Check 2: Admin protection (Admin cannot leave his own channel)
        if ($channel->admin_id === auth()->id()) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Admin cannot leave the channel. Delete the channel instead.'
            ], 400);
        }

        // Instance pass karein taake controller query na kare
        $request->merge(['channel_instance' => $channel]);

        return $next($request);
    }
}