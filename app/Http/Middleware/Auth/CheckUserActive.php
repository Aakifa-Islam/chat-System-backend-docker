<?php
namespace App\Http\Middleware\Auth;

use Closure;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CheckUserActive {
    public function handle($request, Closure $next) {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if (!$user->is_verified) {
            return response()->json(['message' => 'Please verify your email first.'], 403);
        }

        // User object ko request mein pass kar dena taake controller mein dobara query na karni paray
        $request->merge(['authenticated_user' => $user]);

        return $next($request);
    }
}