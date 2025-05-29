<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Login Routes
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    // Registration Routes
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);

    // Password Reset Routes (if you need them)
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});
