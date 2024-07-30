<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthenticationController;

Route::post('register', [AuthenticationController::class, 'register']);
Route::post('login', [AuthenticationController::class, 'login']);

Route::middleware('auth:sanctum')->group( function(){
    Route::get('me', [AuthenticationController::class, 'me']);
    Route::post('me', [AuthenticationController::class, 'update_me']);
    Route::post('logout', [AuthenticationController::class, 'logout']);
});
