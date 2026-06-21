<?php

use App\Http\Controllers\GenreController;
use Illuminate\Support\Facades\Route;

Route::get('/genres', [GenreController::class, 'index']);
Route::get('/genres/{id}', [GenreController::class, 'show']);
Route::post('/genres', [GenreController::class, 'store']);
Route::patch('/genres/{id}', [GenreController::class, 'update']);
Route::delete('/genres/{id}', [GenreController::class, 'destroy']);
