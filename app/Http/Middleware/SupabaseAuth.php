<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class SupabaseAuth
{
    public function handle(Request $request, Closure $next): Response
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
        if (!session()->has('supabase_access_token')) {
            Log::warning('SupabaseAuth Middleware - No Supabase token in session');
            return $this->redirectWithError($request, 'Your session has expired or is invalid. Please log in again.');
        }

        // Verify the token is still valid
        $ch = curl_init(config('services.supabase.url') . '/auth/v1/user');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . session('supabase_access_token'),
            'apikey: ' . config('services.supabase.anon_key')
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            // Token is invalid, try to refresh
            $ch = curl_init(config('services.supabase.url') . '/auth/v1/token?grant_type=refresh_token');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                'refresh_token' => session('supabase_refresh_token')
            ]));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'apikey: ' . config('services.supabase.anon_key')
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                $data = json_decode($response, true);
                session([
                    'supabase_access_token' => $data['access_token'],
                    'supabase_refresh_token' => $data['refresh_token']
                ]);
            } else {
                // Refresh failed, redirect to login
                session()->forget(['supabase_access_token', 'supabase_refresh_token', 'user']);
                return $this->redirectWithError($request, 'Your session has expired. Please log in again.');
            }
        }

        return $next($request);
    }

    protected function redirectWithError(Request $request, string $message)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => $message], 401);
        }

        return redirect('/task-management-system/login')->with('error', $message);
    }
}
