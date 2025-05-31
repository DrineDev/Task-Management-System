<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\ChangeEmailController;
use App\Http\Controllers\ProjectController;
use App\Http\Middleware\SupabaseAuth;
use Illuminate\Support\Facades\Auth;

// Root redirect
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Debug route (remove after testing)
Route::get('/debug-auth', function() {
    return [
        'authenticated' => Auth::check(),
        'user' => Auth::user(),
        'session_id' => session()->getId(),
        'guard' => config('auth.defaults.guard'),
        'session_driver' => config('session.driver'),
    ];
});

// Guest Routes (not authenticated)
Route::middleware('guest')->group(function () {
    // Login Routes
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    // Registration Routes
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);

    // Password Reset Routes
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
});

// Authenticated Routes (requires login)
Route::middleware(['web', SupabaseAuth::class])->group(function () {
    // Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/search', [DashboardController::class, 'search'])->name('dashboard.search');
    Route::get('/dashboard/calendar', [DashboardController::class, 'getCalendarData'])->name('dashboard.calendar');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    
    // Email change routes
    Route::get('/change-email', [ChangeEmailController::class, 'show'])->name('change-email.show');
    Route::put('/change-email', [ChangeEmailController::class, 'update'])->name('change-email.update');
    
    // Password change routes
    Route::get('/change-password', [ChangePasswordController::class, 'show'])->name('change-password.show');
    Route::put('/change-password', [ChangePasswordController::class, 'update'])->name('change-password.update');

    // Projects
    Route::resource('projects', ProjectController::class);
    Route::get('/projects/{project}/tasks', [ProjectController::class, 'tasks'])->name('projects.tasks');

    // Tasks
    Route::resource('tasks', TaskController::class);
    Route::put('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');
    Route::get('/tasks/calendar', [TaskController::class, 'calendar'])->name('tasks.calendar');

    // Logout
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});
