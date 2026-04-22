<?php

use App\Http\Controllers\Vibe\Stage07\MicroserviceController;
use Illuminate\Support\Facades\Route;

Route::get('overview', [MicroserviceController::class, 'overview'])->name('overview');
Route::post('inventory/check', [MicroserviceController::class, 'inventoryCheck'])->name('inventory.check');
Route::post('pricing/quote', [MicroserviceController::class, 'pricingQuote'])->name('pricing.quote');
Route::post('checkout/simulate', [MicroserviceController::class, 'checkoutSimulation'])->name('checkout.simulate');
Route::get('outbox', [MicroserviceController::class, 'outbox'])->name('outbox.index');
