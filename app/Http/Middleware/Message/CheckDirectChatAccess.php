<?php

namespace App\Http\Middleware\Message;

use Closure;
use App\Models\WorkspaceMember;

class CheckDirectChatAccess {
    public function handle($request, Closure $next) {
        // 1. Receiver ID ko ya to body se pakrein ya URL parameter {receiver_id} se
        $receiverId = $request->receiver_id ?? $request->route('receiver_id');

        // 2. Workspace ID ko ya to body se pakrein ya URL query string ?workspace_id=... se
        $workspaceId = $request->workspace_id ?? $request->query('workspace_id');

        // Debugging ke liye: Agar ab bhi error aaye toh niche wali line uncomment karke check karein
        // return response()->json(['r' => $receiverId, 'w' => $workspaceId]);

        if (!$receiverId || !$workspaceId) {
            return response()->json(['message' => 'Receiver and Workspace ID are required'], 422);
        }

        // Eloquent check
        $membersCount = WorkspaceMember::where('workspace_id', $workspaceId)
            ->whereIn('user_id', [auth()->id(), $receiverId])
            ->count();

        if ($membersCount < 2 && auth()->id() !== $receiverId) { 
            return response()->json(['message' => 'Both users must be members of the same workspace'], 403);
        }

        return $next($request);
    }
}