<?php

use App\Http\Controllers\Vibe\Stage04\AuthenticationController;
use App\Http\Controllers\Vibe\Stage04\PasswordResetController;
use App\Http\Controllers\Vibe\Stage04\ProfileController;
use App\Http\Controllers\Vibe\Stage04\SocialLoginController;
use Illuminate\Support\Facades\Route;

Route::get('overview', [AuthenticationController::class, 'overview'])->name('overview');

Route::middleware('guest:web')->group(function () {
    Route::post('register', [AuthenticationController::class, 'register'])->name('register');
    Route::post('login', [AuthenticationController::class, 'login'])->name('login');
    Route::post('forgot-password', [PasswordResetController::class, 'forgot'])->name('password.forgot');
    Route::post('reset-password', [PasswordResetController::class, 'reset'])->name('password.reset');
    Route::post('social/callback', [SocialLoginController::class, 'callback'])->name('social.callback');
});

Route::middleware('auth:web')->group(function () {
    Route::get('me', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('logout', [AuthenticationController::class, 'logout'])->name('logout');
});
