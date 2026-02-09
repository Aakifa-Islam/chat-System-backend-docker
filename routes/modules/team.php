<?php

use App\Http\Controllers\Api\TeamController;
use Illuminate\Support\Facades\Route;

Route::middleware(['check.token'])->group(function () {
    
    // Get all teams doesn't need admin check usually, just token
    Route::get('/workspaces/{workspace_id}/teams', [TeamController::class, 'getAll']);

    // Create Team: Needs Token + Admin Check
    Route::post('/teams/create', [TeamController::class, 'create'])->middleware('check.admin');

    // Update/Delete: Needs Token + Access Check + Admin Check
    Route::middleware(['check.team', 'check.admin'])->group(function () {
        Route::put('/teams/update/{id}', [TeamController::class, 'update']);
        Route::delete('/teams/delete/{id}', [TeamController::class, 'delete']);
    });
});