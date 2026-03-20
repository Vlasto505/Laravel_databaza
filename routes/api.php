<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\CategoryController;

Route::get('notes/filter/pinned', [NoteController::class, 'pinnedNotes']);
Route::get('notes/stats/status', [NoteController::class, 'statsByStatus']);

Route::apiResource('notes', NoteController::class);
Route::apiResource('categories', CategoryController::class);

Route::patch('notes/{id}/publish', [NoteController::class, 'publish']);
Route::patch('notes/{id}/archive', [NoteController::class, 'archive']);
Route::patch('notes/{id}/pin', [NoteController::class, 'togglePin']);
