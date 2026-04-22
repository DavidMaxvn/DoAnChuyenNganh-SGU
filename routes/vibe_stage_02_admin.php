<?php

use App\Http\Controllers\Vibe\Stage02\Admin\AttributeController;
use App\Http\Controllers\Vibe\Stage02\Admin\ProductModelingController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:admin')->group(function () {
    Route::get('overview', [ProductModelingController::class, 'overview'])->name('overview');

    Route::get('attributes', [AttributeController::class, 'index'])->name('attributes.index');
    Route::post('attributes', [AttributeController::class, 'store'])->name('attributes.store');

    Route::put('products/{product}/model', [ProductModelingController::class, 'configure'])->name('products.model.configure');
    Route::get('products/{product}/model', [ProductModelingController::class, 'show'])->name('products.model.show');
    Route::post('products/{product}/variants', [ProductModelingController::class, 'storeVariant'])->name('products.variants.store');
});
