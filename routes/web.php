<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;

Route::get('/', [ProductController::class, 'index']);
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/admin', [AdminController::class, 'index']);

// Endpoints API agrupados usando la persistencia y middleware de "Web" (Session)
Route::group(['prefix' => 'api'], function() {
    Route::get('/products', [ProductController::class, 'getProducts']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::get('/auth/session', [AuthController::class, 'session']);
    Route::post('/orders/checkout', [OrderController::class, 'checkout']);
});
