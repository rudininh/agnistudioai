<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContentIdeaController;
use App\Http\Controllers\Api\ContentItemController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ScheduledPostController;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\WorkspaceController;
use Illuminate\Support\Facades\Route;

// Authentication routes with rate limiting
Route::post('/register', [AuthController::class, 'register'])
    ->middleware('throttle:5,1'); // 5 attempts per minute

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1'); // 5 attempts per minute

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:sanctum');
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');

// Test route
Route::get('/test', [TestController::class, 'hello'])->name('api.test');

// Workspace routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('workspaces', WorkspaceController::class);
    Route::apiResource('workspaces.projects', ProjectController::class);
    Route::apiResource('workspaces.projects.ideas', ContentIdeaController::class);
    Route::apiResource('workspaces.content-items', ContentItemController::class);
    Route::apiResource('workspaces.scheduled-posts', ScheduledPostController::class);
});
