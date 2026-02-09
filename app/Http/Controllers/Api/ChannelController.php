<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Http\Requests\Channel\CreateChannelRequest;
use App\Http\Requests\Channel\UpdateChannelRequest;
use App\Http\Resources\ChannelResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChannelController extends Controller 
{
    // Create Channel
  public function create(CreateChannelRequest $request): JsonResponse 
{
    // Team existence already checked by middleware
    $channel = Channel::create([
        'name'         => $request->name,
        'type'         => $request->type, // public or private
        'team_id'      => $request->team_id,
        'workspace_id' => $request->workspace_id, // Middleware se merge hoke aya
        'admin_id'     => auth()->id(),
        'member_ids'   => [auth()->id()]
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Channel created under Team successfully',
        'data' => new ChannelResource($channel)
    ]);
}

    // Get All Channels for a Team
  // Get All Channels for a Team
    public function ReadAll($team_id): JsonResponse 
    {
        // Middleware ne pehle hi check kar liya hai ke user team/workspace ka member hai
        $channels = Channel::where('team_id', $team_id)->get();
        
        return response()->json([
            'status' => 'success', 
            'data' => ChannelResource::collection($channels)
        ]);
    }

    // Update Channel (Admin Only via Middleware)
  public function update(UpdateChannelRequest $request, $id): JsonResponse 
{
    // Middleware se aya hua instance pakrein
    $channel = $request->channel_instance;

    // Sirf validated data se update karein
    $channel->update($request->validated());
    
    return response()->json([
        'status' => 'success', 
        'message' => 'Channel updated successfully',
        'data' => new ChannelResource($channel)
    ]);
}

    // Delete Channel (Admin Only via Middleware)
    public function delete($id): JsonResponse 
    {
        $channel = Channel::findOrFail($id);
        $channel->delete();
        
        return response()->json([
            'status' => 'success', 
            'message' => 'Channel deleted successfully'
        ]);
    }

    // Join Public Channel
  // Join Public Channel
    public function join(Request $request): JsonResponse 
    {
        // Middleware ne saari checking kar li hai aur instance bhi bhej diya hai
        $channel = $request->channel_instance;
        
        // MongoDB unique push
        $channel->push('member_ids', auth()->id(), true);
        
        return response()->json([
            'status' => 'success', 
            'message' => 'Joined channel successfully'
        ]);
    }

    // Leave Channel
  // Leave Channel
    public function leave(Request $request): JsonResponse 
    {
        $channel = $request->channel_instance;
        
        // MongoDB pull
        $channel->pull('member_ids', auth()->id());
        
        return response()->json([
            'status' => 'success', 
            'message' => 'Left channel successfully'
        ]);
    }
}