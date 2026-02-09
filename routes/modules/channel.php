<?php
use App\Http\Controllers\Api\ChannelController;
use Illuminate\Support\Facades\Route;

Route::middleware(['check.token'])->group(function () {
    
    // Sab se pehle check karo ke user us Team/Workspace ka hissa hai
    Route::middleware(['check.team_member'])->group(function () {
        Route::post('/channels/create', [ChannelController::class, 'create']);
        Route::get('/teams/{team_id}/channels', [ChannelController::class, 'ReadAll']);
    });

    // Channel Admin Only (Update/Delete)
    Route::middleware(['check.ch_admin'])->group(function () {
        Route::put('/channels/update/{id}', [ChannelController::class, 'update']);
        Route::delete('/channels/delete/{id}', [ChannelController::class, 'delete']);
    });

    // Join route par naya suthra middleware laga diya
Route::post('/channels/join/{id}', [ChannelController::class, 'join'])
     ->middleware('check.channel.join');

    // Leave route with its own middleware
Route::post('/channels/leave/{id}', [ChannelController::class, 'leave'])
     ->middleware('check.channel.leave');


     });