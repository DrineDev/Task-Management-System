<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class SupabaseAuth
{
    public function handle(Request $request, Closure $next)
    {
        $routeName = Route::currentRouteName();

        // Avoid infinite redirect loop on login route
        if (in_array($routeName, ['login', 'auth.callback', 'auth.supabase-login'])) {
            return $next($request);
        }

        // Check user is authenticated via Laravel
        if (!Auth::check()) {
            Log::warning('SupabaseAuth Middleware - User not authenticated');

            return $this->redirectWithError($request, 'Please log in to access this page.');
        }

        // Ensure Supabase session exists
        if (!$request->session()->has('supabase_access_token')) {
            Log::warning('SupabaseAuth Middleware - No Supabase token in session');

            return $this->redirectWithError($request, 'Your session has expired or is invalid. Please log in again.');
        }

        // Token expiration check
        $expiresAt = $request->session()->get('supabase_token_expires_at');
        if ($expiresAt && now()->isAfter($expiresAt)) {
            Log::warning('SupabaseAuth Middleware - Supabase token expired', [
                'expires_at' => $expiresAt,
                'now' => now(),
            ]);

            Auth::logout();
            $request->session()->invalidate();

            return $this->redirectWithError($request, 'Your session has expired. Please log in again.');
        }

        return $next($request);
    }

    protected function redirectWithError(Request $request, string $message)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => $message], 401);
        }

        return redirect()->route('login')->with('error', $message);
    }
}
