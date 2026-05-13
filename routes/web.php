<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;

Route::get('/', [ProductController::class, 'home'])->name('home');


// Exponer endpoint de checkout también por web para que el frontend pueda llamarlo
Route::post('/orders/checkout', [OrderController::class, 'checkout']);
Route::get('/dashboard', [ProductController::class, 'index'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('/products/{product}', [AdminController::class, 'destroyProduct'])->name('admin.products.destroy');
    Route::post('/products/{product}/wholesale-prices', [AdminController::class, 'storeWholesalePrice'])->name('admin.products.wholesale.store');
    Route::delete('/wholesale-prices/{wholesalePrice}', [AdminController::class, 'destroyWholesalePrice'])->name('admin.products.wholesale.destroy');
    Route::post('/discounts', [AdminController::class, 'storeDiscount'])->name('admin.discounts.store');
    Route::put('/discounts/{discount}', [AdminController::class, 'updateDiscount'])->name('admin.discounts.update');
    Route::delete('/discounts/{discount}', [AdminController::class, 'destroyDiscount'])->name('admin.discounts.destroy');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    Route::put('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.status');
});

require __DIR__ . '/auth.php';
