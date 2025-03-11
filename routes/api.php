<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StreamApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('streams', StreamApiController::class)
    ->middleware('auth:sanctum')
    ->except(['index']);

Route::get('/streams', [StreamApiController::class, 'index'])->name('streams.index');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
