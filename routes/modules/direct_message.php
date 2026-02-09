<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DirectMessageController;

Route::middleware(['check.token', 'check.direct.chat'])->group(function () {
    // Message bhejne ke liye handle.gridfs pehle file upload karega
    Route::post('/', [DirectMessageController::class, 'create'])->middleware('handle.gridfs');
    
    // Conversation history dekhne ke liye
    Route::get('/history/{receiver_id}', [DirectMessageController::class, 'readAll']);
});