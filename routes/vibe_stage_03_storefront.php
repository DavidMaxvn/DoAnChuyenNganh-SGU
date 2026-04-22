<?php

use App\Http\Controllers\Vibe\Stage03\StorefrontController;
use Illuminate\Support\Facades\Route;

Route::get('overview', [StorefrontController::class, 'overview'])->name('overview');
Route::get('home', [StorefrontController::class, 'home'])->name('home');
Route::get('products', [StorefrontController::class, 'catalog'])->name('products.index');
Route::get('search', [StorefrontController::class, 'search'])->name('search');
Route::get('products/{product}', [StorefrontController::class, 'show'])->name('products.show');
