<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\ImageController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\CategoryController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\NotificationController;
use App\Http\Controllers\Web\AuthenticationController;

Route::middleware('guest')->group(function(){
    Route::get('login', [AuthenticationController::class, 'login'])->name('login');
    Route::get('register', [AuthenticationController::class, 'register'])->name('register');
    Route::post('store', [AuthenticationController::class, 'store'])->name('store');
    Route::post('authenticate', [AuthenticationController::class, 'authenticate'])->name('authenticate');
});

Route::middleware('auth')->group(function(){
    // Dashboard
    Route::get('/', [DashboardController::class,'index'])->name('dashboard');

    // User Authentication
    Route::post('logout', [AuthenticationController::class, 'logout'])->name('logout');

    // User Profile
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::get('change-password', [UserController::class, 'profile'])->name('change-password');
    Route::post('update-profile', [UserController::class, 'update_profile'])->name('update-profile');
    Route::post('update-password', [UserController::class, 'change_password'])->name('update-password');


    // Users
    Route::prefix('users')->group(function(){
        Route::get('/', [UserController::class, 'list_users'])->name('users.list');
        Route::get('export', [UserController::class, 'export'])->name('users.export');
        Route::get('customer/export', [UserController::class, 'export_customer'])->name('users.export_customer');
        Route::get('{user}', [UserController::class, 'user_detail'])->name('users.detail');
        Route::post('delete-account', [UserController::class, 'delete_account'])->name('delete-account');
        Route::post('load', [UserController::class, 'load_data'])->name('users.load_data');
    });

    // Product
    Route::prefix('products')->group(function(){
        Route::post('upload-image', [ImageController::class, 'uploadImage'])->name('upload_image');
        Route::post('delete-image', [ImageController::class, 'deleteImage'])->name('upload_image');
        Route::post('load', [ProductController::class, 'loadData'])->name('products.load_data');
        Route::get('export', [ProductController::class, 'export'])->name('products.export');
        Route::post('import', [ProductController::class, 'import'])->name('products.import');
        Route::get('templates', [ProductController::class, 'templates'])->name('products.templates');
    });

    // Category
    Route::prefix('categories')->group(function(){
        Route::post('load', [CategoryController::class, 'load_data'])->name('categories.load_data');
        Route::get('export', [CategoryController::class, 'export'])->name('categories.export');
        Route::post('import', [CategoryController::class, 'import'])->name('categories.import');
        Route::get('templates', [CategoryController::class, 'templates'])->name('categories.templates');
    });


    // Order
    Route::prefix('orders')->group(function() {
        Route::get('/', [OrderController::class, 'index'])->name('orders.list');
        Route::post('load', [OrderController::class, 'load_data'])->name('orders.load_data');
        Route::get('export', [OrderController::class, 'export'])->name('orders.export');
        Route::get('{id}', [OrderController::class, 'detail'])->name('orders.detail');
        Route::post('{order_id}/cancel', [OrderController::class, 'cancel_order'])->name('orders.cancel');
    });

    Route::prefix('notifications')->group(function() {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications');
        Route::get('detail/{id}', [NotificationController::class, 'detail'])->name('notifications.detail');
        Route::get('markAsRead/{id}', [NotificationController::class, 'mark'])->name('notifications.markAsRead');
        Route::post('remove/{id}', [NotificationController::class, 'remove'])->name('notifications.remove');
        Route::get('readAll', [NotificationController::class, 'readAll'])->name('notifications.readAll');
        Route::get('RemoveAll', [NotificationController::class, 'removeAll'])->name('notifications.removeAll');
    });

    Route::resources([
        'categories'=> CategoryController::class,
        'products' => ProductController::class,
    ]);
});
