<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AuthController;

Route::prefix('auth')->group(function () {
     Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout-all', [AuthController::class, 'logoutAll']);
        Route::patch('/change-password', [AuthController::class, 'changePassword']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
    });

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/verified-only', function () {
            return response()->json(['message' => 'Email je potvrdeny!']);
        })->middleware('verified');
    });
});


Route::get('notes/filter/pinned', [NoteController::class, 'pinnedNotes']);
Route::get('notes/stats/status', [NoteController::class, 'statsByStatus']);

Route::apiResource('notes', NoteController::class);
Route::apiResource('categories', CategoryController::class);

Route::patch('notes/{id}/publish', [NoteController::class, 'publish']);
Route::patch('notes/{id}/archive', [NoteController::class, 'archive']);
Route::patch('notes/{id}/pin', [NoteController::class, 'togglePin']);

Route::apiResource('notes.tasks', TaskController::class)->scoped();
