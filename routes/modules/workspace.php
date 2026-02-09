<?php
use App\Http\Controllers\Api\WorkspaceController;
use Illuminate\Support\Facades\Route;

Route::middleware(['check.token'])->group(function () {
    Route::post('/workspaces/create', [WorkspaceController::class, 'create']);
    Route::post('/workspaces/join', [WorkspaceController::class, 'join']); // Use code to join
    
    // Admin middleware protect removing members
    Route::delete('/workspaces/{workspace_id}/remove-member/{user_id}', [WorkspaceController::class, 'removeMember'])
         ->middleware('check.admin');
});