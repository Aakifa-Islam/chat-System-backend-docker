<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MessageController;

Route::middleware(['check.token'])->group(function () {
    
    // Create & ReadAll
    Route::middleware(['check.channel.member'])->group(function () {
        // Sirf is route par file upload handler lagaya hai
        Route::post('/message', [MessageController::class, 'create'])->middleware('handle.gridfs');
        Route::get('/message/all/{channel_id}', [MessageController::class, 'readAll']);
    });

    // Update & Delete
    Route::middleware(['check.message.owner'])->group(function () {
        Route::put('/message/update/{id}', [MessageController::class, 'update']);
        Route::delete('/message/delete/{id}', [MessageController::class, 'delete']);
    });

    // File View/Download
    Route::get('/message/file/{id}', [MessageController::class, 'readFile'])
        ->middleware(['check.file.access']);
});