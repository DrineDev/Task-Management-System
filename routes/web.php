<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\ChangeEmailController;
use Illuminate\Support\Facades\Auth;

// Root redirect
Route::get('/', function () {
    return redirect()->route('dashboard');
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


// Social Login Route
Route::get('/auth/{provider}', [SocialController::class, 'redirectToProvider'])
     ->name('auth.provider');
// Authenticated Routes (requires login)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/search', [DashboardController::class, 'search'])->name('dashboard.search');
    Route::get('/dashboard/calendar', [DashboardController::class, 'getCalendarData'])->name('dashboard.calendar');

    // Project routes - Updated to use ProjectController
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/api/projects/{projectId}', [ProjectController::class, 'getProject'])->name('api.projects.get');
    Route::put('/projects/{projectId}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{projectId}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::post('/projects/{projectId}/toggle-complete', [ProjectController::class, 'toggleComplete'])->name('projects.toggle-complete');

    // Task routes
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{id}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::post('/tasks/{id}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
    Route::patch('/tasks/{taskId}/progress', [TaskController::class, 'updateProgress'])->name('tasks.progress');
    Route::post('/tasks/{taskId}/toggle-complete', [TaskController::class, 'toggleComplete'])->name('tasks.toggle-complete');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'deleteAccount'])->name('profile.delete');

    // Email change routes
    Route::get('/change-email', [ChangeEmailController::class, 'show'])->name('change-email.show');
    Route::put('/change-email', [ChangeEmailController::class, 'update'])->name('change-email.update');

    // Password change routes
    Route::get('/change-password', [ChangePasswordController::class, 'show'])->name('change-password.show');
    Route::put('/change-password', [ChangePasswordController::class, 'update'])->name('change-password.update');

    // Logout
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});

// OAuth callback endpoints (no auth required)
Route::get('/auth/callback', [LoginController::class, 'supabaseCallback'])->name('auth.callback');
Route::get('/auth/{provider}', [LoginController::class, 'redirectToProvider'])->name('auth.provider')->where('provider', 'google|facebook');