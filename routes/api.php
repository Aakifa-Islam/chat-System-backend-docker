<?php

use Illuminate\Support\Facades\Route;

// Auth Routes
require __DIR__ . '/modules/auth.php';

// Workspace Routes
require __DIR__ . '/modules/workspace.php';

// Team Routes
require __DIR__ . '/modules/team.php';

// Channel Routes
require __DIR__ . '/modules/channel.php';

// Channel Messages Routes
require __DIR__ . '/modules/message.php';

// --- DIRECT MESSAGING ROUTES ---
// Hum prefix 'direct-message' laga rahe hain taake routes suthre rahein
Route::prefix('direct-message')->group(base_path('routes/modules/direct_message.php'));

// Health Check
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});