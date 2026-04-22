<?php

use App\Http\Controllers\Vibe\Stage01\Admin\AuthController;
use App\Http\Controllers\Vibe\Stage01\Admin\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:admin')->group(function () {
    Route::get('login', [AuthController::class, 'info'])->name('login.info');
    Route::post('login', [AuthController::class, 'login'])->name('login');
});

Route::middleware('auth:admin')->group(function () {
    Route::get('dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::post('products', [ProductController::class, 'store'])->name('products.store');
    Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');
});
