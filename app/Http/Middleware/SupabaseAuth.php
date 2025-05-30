<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SupabaseAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            Log::warning('SupabaseAuth Middleware - User not authenticated');
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }

        // Check if we have a valid Supabase token
        if (!$request->session()->has('supabase_access_token')) {
            Log::warning('SupabaseAuth Middleware - No Supabase token found');
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }

        // Check if the token is expired
        $expiresAt = $request->session()->get('supabase_token_expires_at');
        if ($expiresAt && now()->isAfter($expiresAt)) {
            Log::warning('SupabaseAuth Middleware - Token expired', [
                'expires_at' => $expiresAt,
                'current_time' => now()
            ]);
            
            Auth::logout();
            $request->session()->forget([
                'supabase_access_token',
                'supabase_refresh_token',
                'supabase_token_expires_at'
            ]);
            
            return redirect()->route('login')->with('error', 'Your session has expired. Please log in again.');
        }

        return $next($request);
    }
} 