<?php
namespace App\Http\Middleware\Message;

use Closure;
use App\Models\Channel;
use Illuminate\Support\Facades\Storage;

class CheckChannelMember {
    public function handle($request, Closure $next) {
        $channel = Channel::find($request->channel_id);

        if (!$channel || !in_array(auth()->id(), $channel->member_ids ?? [])) {
            return response()->json(['message' => 'Unauthorized or Channel not found'], 403);
        }

        // Agar file hai, to usay GridFS mein bhej dein (Laravel Storage abstraction use karte hue)
      // CheckChannelMember.php mein upload logic update karein
if ($request->hasFile('attachment')) {
    $file = $request->file('attachment');
    
    // Custom connection nahi, balkay default mongodb connection se bucket uthayein
    $bucket = \Illuminate\Support\Facades\DB::connection('mongodb')
                ->getMongoDB()
                ->selectGridFSBucket();

    $fileStream = fopen($file->getRealPath(), 'rb');
    
    // File upload aur ID hasil karna
    $fileId = $bucket->uploadFromStream($file->getClientOriginalName(), $fileStream);

    $request->merge([
        'grid_file_id' => $fileId, 
        'original_name' => $file->getClientOriginalName()
    ]);
}

        return $next($request);
    }
}