<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\AuthenticationController;

Route::post('register', [AuthenticationController::class, 'register']);
Route::post('login', [AuthenticationController::class, 'login']);

Route::middleware('auth:sanctum')->group( function(){
    Route::get('me', [AuthenticationController::class, 'me']);
    Route::post('me', [AuthenticationController::class, 'update_me']);
    Route::post('logout', [AuthenticationController::class, 'logout']);

    Route::get('products', [ProductController::class, 'products']);
    Route::get('products/category/{category}', [ProductController::class, 'products_by_category']);
    Route::get('products/sort-price/{sort_by}', [ProductController::class, 'sort_price']);
    Route::get('products/{product}', [ProductController::class, 'product_detail']);
});
