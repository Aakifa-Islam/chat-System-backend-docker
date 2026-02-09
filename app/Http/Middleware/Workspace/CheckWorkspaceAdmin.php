<?php

namespace App\Http\Middleware\Workspace;

use Closure;
use App\Models\Workspace;
use Illuminate\Http\Request;

class CheckWorkspaceAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $workspaceId = $request->workspace_id ?? $request->route('workspace_id');

        if (!$workspaceId) {
            return response()->json(['status' => 'error', 'message' => 'Workspace ID is missing'], 400);
        }

        $workspace = Workspace::find($workspaceId);

        if (!$workspace || $workspace->owner_id !== auth()->id()) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Unauthorized. Only Workspace Admin can perform this action.'
            ], 403);
        }

        return $next($request);
    }
}