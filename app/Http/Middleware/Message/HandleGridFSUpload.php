<?php
namespace App\Http\Middleware\Message;

use Closure;
use Illuminate\Support\Facades\DB;

class HandleGridFSUpload {
    public function handle($request, Closure $next) {
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $bucket = DB::connection('mongodb')->getMongoDB()->selectGridFSBucket();
            $fileStream = fopen($file->getRealPath(), 'rb');
            
            $fileId = $bucket->uploadFromStream($file->getClientOriginalName(), $fileStream);

            $request->merge([
                'grid_file_id' => $fileId,
                'original_name' => $file->getClientOriginalName()
            ]);
        }
        return $next($request);
    }
}