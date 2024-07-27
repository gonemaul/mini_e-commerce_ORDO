<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Authentication;

Route::get('/', function () {
    return view('dashboard')->with(['title' => 'Dashboard']);
})->name('dashboard')->middleware('auth');

Route::get('login', [Authentication::class, 'login'])->name('login');
Route::get('register', [Authentication::class, 'register'])->name('register');
Route::post('authenticate', [Authentication::class, 'authenticate'])->name('authenticate');
Route::post('store', [Authentication::class, 'store'])->name('store');
Route::post('logout', [Authentication::class, 'logout'])->name('logout');


