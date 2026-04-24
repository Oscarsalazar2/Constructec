<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;

Route::get('/products', [ProductController::class, 'getProducts']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/auth/session', [AuthController::class, 'session']);
Route::post('/orders/checkout', [OrderController::class, 'checkout']);
