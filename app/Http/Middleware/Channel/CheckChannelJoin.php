<?php

namespace App\Http\Middleware\Channel;

use Closure;
use App\Models\Channel;
use App\Models\Workspace;
use Illuminate\Http\Request;

class CheckChannelJoin
{
    public function handle(Request $request, Closure $next)
    {
        $channelId = $request->route('id');
        $channel = Channel::find($channelId);

        if (!$channel) {
            return response()->json(['status' => 'error', 'message' => 'Channel not found'], 404);
        }

        // 1. Workspace Membership Check
        $workspace = Workspace::find($channel->workspace_id);
        if (!$workspace || !in_array(auth()->id(), $workspace->member_ids ?? [])) {
            return response()->json([
                'status' => 'error',
                'message' => 'You must be a member of the workspace to join its channels'
            ], 403);
        }

        // 2. Private Channel Check
        if ($channel->type === 'private') {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot join a private channel without an invitation'
            ], 403);
        }

        // Data aage pass kar dein taake controller ko dobara query na karni paray
        $request->merge(['channel_instance' => $channel]);

        return $next($request);
    }
}