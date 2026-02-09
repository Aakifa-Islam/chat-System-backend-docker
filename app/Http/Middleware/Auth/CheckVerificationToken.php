<?php
namespace App\Http\Middleware\Auth;

use Closure;
use App\Models\User;

class CheckVerificationToken {
   public function handle($request, Closure $next) {
        $user = User::find($request->route('id'));
        $token = $request->route('token');

        if (!$user || $user->verification_token !== $token) {
            return response()->json(['message' => 'Invalid link!'], 401);
        }

        // Ye line lazmi honi chahiye
        $request->merge(['user_to_verify' => $user]);

        return $next($request);
    }
}