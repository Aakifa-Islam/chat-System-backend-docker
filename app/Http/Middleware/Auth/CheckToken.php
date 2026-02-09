<?php
namespace App\Http\Middleware\Auth;

use Closure;
use App\Models\User;

class CheckToken {
    public function handle($request, Closure $next) {
    $bearerToken = $request->bearerToken();

    if (!$bearerToken) {
        return response()->json(['message' => 'Token not provided'], 401);
    }

    // Token table mein search karein aur sath User bhi load karein
    $tokenData = \App\Models\Token::where('token', $bearerToken)->with('user')->first();

    if (!$tokenData || !$tokenData->user) {
        return response()->json(['message' => 'Invalid or expired token'], 401);
    }

    // Auth manually set karein
    auth()->setUser($tokenData->user);
    
    // Request mein current token ki ID save kar len taake logout mein asani ho
    $request->merge(['current_token' => $bearerToken]);

    return $next($request);
}
}