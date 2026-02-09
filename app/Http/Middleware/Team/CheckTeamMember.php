<?php

namespace App\Http\Middleware\Team;

use Closure;
use App\Models\Team;
use Illuminate\Http\Request;

class CheckTeamMember 
{
    public function handle(Request $request, Closure $next) 
    {
        // Check karein ke ID body mein hai ya URL parameter mein
        $teamId = $request->input('team_id') ?? $request->route('team_id');

        if (!$teamId) {
            return response()->json(['status' => 'error', 'message' => 'Team ID missing'], 400);
        }

        // MongoDB mein hamesha find() use karein _id ke liye
        $team = Team::with('workspace')->find($teamId);

        if (!$team) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Team not found',
                'debug_id' => $teamId // Sirf testing ke liye taake pata chale kya ID aa rahi hai
            ], 404);
        }

        // Workspace member check
        if (!in_array(auth()->id(), $team->workspace->member_ids ?? [])) {
            return response()->json([
                'status' => 'error',
                'message' => 'You must be a workspace member to access this team'
            ], 403);
        }

        // Data pass karein taake controller query na kare
        $request->merge(['workspace_id' => $team->workspace_id]);

        return $next($request);
    }
}