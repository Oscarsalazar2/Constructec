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
Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
