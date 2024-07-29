<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Authentication;
use App\Http\Controllers\Web\UserController;

Route::get('/', function () {
    return view('dashboard')->with(['title' => 'Dashboard']);
})->name('dashboard')->middleware('auth');

Route::middleware('guest')->group(function(){
    Route::get('login', [Authentication::class, 'login'])->name('login');
    Route::get('register', [Authentication::class, 'register'])->name('register');
    Route::post('store', [Authentication::class, 'store'])->name('store');
    Route::post('authenticate', [Authentication::class, 'authenticate'])->name('authenticate');
});

Route::middleware('auth')->group(function(){
    Route::post('logout', [Authentication::class, 'logout'])->name('logout');
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::get('change-password', [UserController::class, 'profile'])->name('change-password');
    Route::post('update-profile', [UserController::class, 'update_profile'])->name('update-profile');
    Route::post('update-password', [UserController::class, 'change_password'])->name('update-password');
    Route::get('users', [UserController::class, 'list_users'])->name('users.list');
    Route::get('user/{user}', [UserController::class, 'user_detail'])->name('users.detail');
});
