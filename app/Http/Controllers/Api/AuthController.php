<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\Auth\RegisterRequest;
use App\Notifications\Auth\VerifyEmailNotification;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Http\Requests\Auth\UpdateUserRequest;
use App\Http\Requests\Auth\ForgetPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Notifications\Auth\ResetPasswordOTPNotification;
use App\Models\Token;


class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        // 1. User create via Model logic (Fix: no space in ::add)
        $user = User::add($request->validated());

        // 2. Token generate aur save
        $token = Str::random(40);
        $user->update(['verification_token' => $token]);

        // 3. Direct Notification (No Queue, foran mail jayegi)
        // Agar mail fail hui, to yehi line error dikha degi Postman mein
        $user->notify(new VerifyEmailNotification($user, $token));

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully! Please check your email for verification link.'
        ], 201);
    }
    
     // Email Verification Logic
    public function verify(Request $request)
    {
        // Middleware 'check.verify.token' ne user pehle hi dhund kar request mein daal diya hy
        $user = $request->user_to_verify;

        // User ka status update karein
        $user->update([
            'is_verified' => true,
            'email_verified_at' => now(),
            'verification_token' => null // Token use ho gaya, ab khatam
        ]);

        // Success View return karein jo browser mein dikhega
        return view('auth.verified_success', ['name' => $user->name]);
    }


   public function login(LoginRequest $request): JsonResponse 
{
    $user = $request->authenticated_user;
    $tokenValue = \Illuminate\Support\Str::random(80);

    // YAHAN MASLA HO SAKTA HAI:
    // Direct Token model use karke create karein
    \App\Models\Token::create([
        'user_id'    => $user->_id, // MongoDB ki ID use karein
        'token'      => $tokenValue,
        'expires_at' => now()->addDays(30)
    ]);

    // User table mein ab sirf status update hoga, token nahi
    $user->update(['status' => 'online']);

    return response()->json([
        'status'  => 'success',
        'token'   => $tokenValue,
        'user'    => new \App\Http\Resources\UserResource($user)
    ]);
}

    // 1. Get All Users (Read)
public function getAllUsers(): JsonResponse {
    $users = User::all();
    return response()->json([
        'status' => 'success',
        'data'   => UserResource::collection($users)
    ]);
}

// 2. Update User (Update)
public function update(UpdateUserRequest $request): JsonResponse {
    $user = auth()->user();
    $user->update($request->validated());

    return response()->json([
        'status'  => 'success',
        'message' => 'Profile updated successfully',
        'data'    => new UserResource($user)
    ]);
}

// 3. Delete User (Delete)
public function delete(): JsonResponse {
    $user = auth()->user();
    $user->delete();

    return response()->json([
        'status'  => 'success',
        'message' => 'Account deleted successfully'
    ]);
}
    

// Forget Password - Sends OTP
public function forgetPassword(ForgetPasswordRequest $request): JsonResponse {
    $user = User::where('email', $request->email)->first();
    
    // Generate 6 digit OTP
    $otp = rand(100000, 999999);
    
    // Save OTP and expiry in user model
    $user->update([
        'reset_otp' => $otp,
        'otp_expiry' => now()->addMinutes(10)
    ]);

    // Send Mail
    $user->notify(new ResetPasswordOTPNotification($otp));

    return response()->json(['status' => 'success', 'message' => 'OTP sent to your email.']);
}

// Reset Password - Verifies OTP and Updates Password
public function resetPassword(ResetPasswordRequest $request): JsonResponse {
    $user = User::where('email', $request->email)->first();

    // Check OTP and Expiry
    if ($user->reset_otp != $request->otp || now()->gt($user->otp_expiry)) {
        return response()->json(['status' => 'error', 'message' => 'Invalid or expired OTP.'], 401);
    }

    // Update Password and Clear OTP
    $user->update([
        'password' => \Hash::make($request->password),
        'reset_otp' => null,
        'otp_expiry' => null
    ]);

    return response()->json(['status' => 'success', 'message' => 'Password has been reset successfully.']);
}

// Logout Function
public function logout(Request $request): JsonResponse {
    $user = auth()->user();
    $currentToken = $request->current_token;

    // Sirf wo wala token delete karein jis se login hain
    \App\Models\Token::where('token', $currentToken)->delete();

    // Agar user ka koi aur token nahi bacha, to status offline kar dein
    if ($user->tokens()->count() == 0) {
        $user->update(['status' => 'offline']);
    }

    return response()->json([
        'status'  => 'success',
        'message' => 'Logged out successfully!'
    ]);
}


}