<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Simple and Easy Routes
// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->middleware('check.active');
Route::get('/verify/{id}/{token}', [AuthController::class, 'verify'])->middleware('check.verify.token');
Route::post('/forget-password', [AuthController::class, 'forgetPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// Protected Routes (Token Required)
Route::middleware('check.token')->group(function () {
    Route::get('/users', [AuthController::class, 'getAllUsers']);      // Get All
    Route::put('/user/update', [AuthController::class, 'update']); // Update
    Route::delete('/user/delete', [AuthController::class, 'delete']); // Delete
    Route::post('/logout', [AuthController::class, 'logout']);
});