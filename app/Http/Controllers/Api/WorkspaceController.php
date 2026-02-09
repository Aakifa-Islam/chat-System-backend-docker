<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
use App\Models\User;
use App\Http\Requests\Workspace\CreateWorkspaceRequest;
use App\Http\Requests\Workspace\JoinWorkspaceRequest;
use App\Http\Resources\WorkspaceResource;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class WorkspaceController extends Controller {

    // 1. Create Workspace with Auto-generated Invite Code
    public function create(CreateWorkspaceRequest $request): JsonResponse {
        $workspace = Workspace::create([
            'name'        => $request->name,
            'description' => $request->description,
            'owner_id'    => auth()->id(),
            'invite_code' => strtoupper(Str::random(8)), // Unique Code
            'member_ids'  => [auth()->id()] // Owner is first member
        ]);

        return response()->json([
            'status' => 'success',
            'data'   => new WorkspaceResource($workspace)
        ]);
    }

    // 2. Join Workspace using Code
    public function join(JoinWorkspaceRequest $request): JsonResponse {
        $workspace = Workspace::where('invite_code', $request->invite_code)->first();
        $userId = auth()->id();

        // Check if already a member
        $members = $workspace->member_ids ?? [];
        if (in_array($userId, $members)) {
            return response()->json(['message' => 'You are already a member'], 400);
        }

        // Add user to member_ids array
        $workspace->push('member_ids', $userId);

        return response()->json([
            'status'  => 'success',
            'message' => 'Joined successfully',
            'data'    => new WorkspaceResource($workspace)
        ]);
    }

    // 3. Remove Member (Admin Only)
    public function removeMember(JsonResponse $request, $workspace_id, $user_id): JsonResponse {
        $workspace = Workspace::find($workspace_id);
        
        // Owner cannot remove themselves
        if ($user_id === $workspace->owner_id) {
            return response()->json(['message' => 'Cannot remove the admin'], 400);
        }

        $workspace->pull('member_ids', $user_id);

        return response()->json(['status' => 'success', 'message' => 'Member removed']);
    }
}