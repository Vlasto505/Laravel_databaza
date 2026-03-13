<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('notes/stats/status', [NoteController::class, 'statsByStatus']);
Route::apiResource('notes', NoteController::class);

Route::apiResource('categories', CategoryController::class);
Route::get('notes/filter/pinned', [NoteController::class, 'pinnedNotes']);
