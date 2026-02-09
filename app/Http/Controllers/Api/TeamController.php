<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\Workspace;
use App\Http\Requests\Team\CreateTeamRequest;
use App\Http\Requests\Team\UpdateTeamRequest;
use App\Http\Resources\TeamResource;
use Illuminate\Http\JsonResponse;

class TeamController extends Controller {

    public function getAll($workspace_id): JsonResponse {
        $teams = Team::where('workspace_id', $workspace_id)->get();
        return response()->json([
            'status' => 'success',
            'data'   => TeamResource::collection($teams)
        ]);
    }

    public function create(CreateTeamRequest $request): JsonResponse {
        $team = Team::create($request->validated());
        return response()->json([
            'status' => 'success',
            'message' => 'Team created successfully',
            'data' => new TeamResource($team)
        ]);
    }

    public function update(UpdateTeamRequest $request, $id): JsonResponse {
        $team = Team::findOrFail($id);
        $team->update($request->validated());
        return response()->json([
            'status' => 'success',
            'message' => 'Team updated successfully',
            'data' => new TeamResource($team)
        ]);
    }

    public function delete($id): JsonResponse {
        $team = Team::findOrFail($id);
        $team->delete();
        return response()->json(['status' => 'success', 'message' => 'Team deleted']);
    }
}