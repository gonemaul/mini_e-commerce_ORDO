<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\AuthenticationController;

// Email Verification
Route::post('/email/verify/send', [AuthenticationController::class, 'verifySend'])->middleware(['throttle:6,1'])->name('api.verification.resend');

Route::middleware(['guest'])->group( function(){
    Route::post('register', [AuthenticationController::class, 'register']);
    Route::post('login', [AuthenticationController::class, 'login']);
    // Forgot Password
    Route::post('forgot-password/reset', [AuthenticationController::class, 'forgot_reset']);
    Route::post('forgot-password/request', [AuthenticationController::class, 'forgot_request']);
});

Route::middleware(['auth:sanctum' , 'verified'])->group( function(){
    Route::get('me', [AuthenticationController::class, 'me']);
    Route::post('me', [AuthenticationController::class, 'update_me']);
    Route::post('logout', [AuthenticationController::class, 'logout']);

    // Product Management
    Route::get('products', [ProductController::class, 'products']);
    Route::get('products/category/{category}', [ProductController::class, 'products_by_category']);
    Route::get('products/sort-price', [ProductController::class, 'sort_price']);
    Route::get('products/{product}', [ProductController::class, 'product_detail']);

    // Cart Management
    Route::post('/cart/{product}', [CartController::class, 'add_to_cart']);
    Route::get('/cart' , [CartController::class, 'show']);
    Route::put('/cart/update/{product}', [CartController::class, 'update_cart']);
    Route::delete('/cart/{product}', [CartController::class, 'remove_from_cart']);

    // Order Management
    Route::prefix('orders')->group(function(){
        Route::post('/', [OrderController::class, 'checkout_orders']);
        Route::get('history', [OrderController::class, 'order_history']);
        Route::post('{order_id}/cancel', [OrderController::class, 'cancel_order']);
    });
    // Invoice
    Route::get('invoice/{order_id}', [OrderController::class, 'invoice']);

    // Notification
    Route::prefix('notifications')->group(function(){
        Route::get('/', [NotificationController::class, 'index']);
        Route::post('markAsRead/{id}', [NotificationController::class, 'mark']);
        Route::delete('remove/{id}', [NotificationController::class, 'remove']);
    });
});
// Midtrans
Route::post('midtrans-callback', [MidtransController::class, 'callback']);
