<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\SocialController; // ✅ Import this

Route::get('/', function () {
    return redirect()->route('login');
});

// Social Login Route
Route::get('/auth/{provider}', [SocialController::class, 'redirectToProvider'])
     ->name('auth.provider');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');

Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');

// Auth scaffolding routes
require __DIR__.'/auth.php';
