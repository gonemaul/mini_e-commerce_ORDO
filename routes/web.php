<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\CategoryController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\AuthenticationController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::middleware('guest')->group(function(){
    Route::get('login', [AuthenticationController::class, 'login'])->name('login');
    Route::get('register', [AuthenticationController::class, 'register'])->name('register');
    Route::post('store', [AuthenticationController::class, 'store'])->name('store');
    Route::post('authenticate', [AuthenticationController::class, 'authenticate'])->name('authenticate');
});

Route::middleware('auth')->group(function(){
    Route::get('/', [DashboardController::class,'index'])->name('dashboard');
    // User
    Route::post('logout', [AuthenticationController::class, 'logout'])->name('logout');
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::get('change-password', [UserController::class, 'profile'])->name('change-password');
    Route::post('update-profile', [UserController::class, 'update_profile'])->name('update-profile');
    Route::post('update-password', [UserController::class, 'change_password'])->name('update-password');
    Route::get('users', [UserController::class, 'list_users'])->name('users.list');
    Route::get('user/{user}', [UserController::class, 'user_detail'])->name('users.detail');

    // Category
    Route::resources([
        'categories'=> CategoryController::class,
        'products' => ProductController::class,
    ]);
});
