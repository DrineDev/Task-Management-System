<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /* Show login form */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->intended('/dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $supabaseUrl = config('services.supabase.url');
        $supabaseAnonKey = config('services.supabase.anon_key');

        if (!$supabaseUrl || !$supabaseAnonKey) {
            Log::error('Supabase credentials missing for login.');
            return redirect()->back()->with('error', 'Authentication service is misconfigured. Please contact support.')->withInput();
        }

        $authUrl = $supabaseUrl . '/auth/v1/token?grant_type=password';

        try {
            Log::info('Attempting Supabase login', [
                'url' => $authUrl,
                'email' => $request->email
            ]);

            $response = Http::withHeaders([
                'apikey' => $supabaseAnonKey,
                'Content-Type' => 'application/json',
            ])->post($authUrl, [
                'email' => $request->email,
                'password' => $request->password,
            ]);

            Log::info('Supabase login response', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $user = User::fromSupabase($data['user']);

                $request->session()->put('supabase_access_token', $data['access_token']);
                $request->session()->put('supabase_refresh_token', $data['refresh_token'] ?? null);
                $request->session()->put('supabase_token_expires_at', now()->addSeconds($data['expires_in']));

                Auth::login($user);
                $request->session()->regenerate();

                return redirect()->intended('/dashboard')->with('success', 'Successfully Logged in!');
            }

                $errorData = $response->json();
                $errorMessage = $errorData['error_description'] ?? ($errorData['message'] ?? 'Invalid login credentials or user does not exist.');

            Log::warning('Supabase login failed for email: ' . $request->email . ' - Supabase Error: ' . $errorMessage, [
                'response_body' => $errorData,
                'status_code' => $response->status()
            ]);
                return redirect()->back()->with('error', $errorMessage)->withInput();
        } catch (\Exception $e) {
            Log::error('General error during Supabase login', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'url' => $authUrl,
                'email' => $request->email
            ]);
            return redirect()->back()->with('error', 'An unexpected error occurred. Please try again')->withInput();
        }
    }

    public function redirectToProvider($provider)
    {
        $supabaseUrl = config('services.supabase.url');
        
        Log::info('OAuth Redirect Details:', [
            'provider' => $provider,
            'supabase_url' => $supabaseUrl,
            'supabase_url_from_env' => env('SUPABASE_URL'),
            'callback_url' => $supabaseUrl . '/auth/v1/callback',
            'request_url' => request()->url(),
            'request_host' => request()->getHost()
        ]);
        
        $authUrl = $supabaseUrl . '/auth/v1/authorize?' . http_build_query([
            'provider' => $provider,
            'redirect_to' => $supabaseUrl . '/auth/v1/callback',
            'scopes' => 'email profile',
        ]);

        Log::info('Generated auth URL:', [
            'url' => $authUrl
        ]);

        return redirect($authUrl);
    }

    public function supabaseCallback(Request $request)
    {
        $code = $request->get('code');
        $error = $request->get('error');
        $errorDescription = $request->get('error_description');

        if ($error) {
            Log::error('OAuth error', [
                'error' => $error,
                'description' => $errorDescription
            ]);
            return redirect()->route('login')->with('error', 'Authentication failed: ' . $errorDescription);
        }

        if (!$code) {
            return redirect()->route('login')->with('error', 'No authorization code received');
        }

        $supabaseUrl = config('services.supabase.url');
        $supabaseAnonKey = config('services.supabase.anon_key');

        try {
            $response = Http::withHeaders([
                'apikey' => $supabaseAnonKey,
                'Content-Type' => 'application/json',
            ])->post($supabaseUrl . '/auth/v1/token', [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_to' => $supabaseUrl . '/auth/v1/callback',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $user = User::fromSupabase($data['user']);
                
                $request->session()->put('supabase_access_token', $data['access_token']);
                $request->session()->put('supabase_refresh_token', $data['refresh_token'] ?? null);
                $request->session()->put('supabase_token_expires_at', now()->addSeconds($data['expires_in']));
                
                Auth::login($user);
                $request->session()->regenerate();

                return redirect()->intended('/dashboard')->with('success', 'Successfully logged in!');
            }

            Log::error('Failed to exchange code for token', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);
            return redirect()->route('login')->with('error', 'Failed to complete authentication');
        } catch (\Exception $e) {
            Log::error('Error during OAuth callback', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('login')->with('error', 'An error occurred during authentication');
        }
    }

    public function logout(Request $request)
    {
        $supabaseUrl = config('services.supabase.url');
        $supabaseAnonKey = config('services.supabase.anon_key');
        $accessToken = $request->session()->get('supabase_access_token');

        if ($supabaseUrl && $supabaseAnonKey && $accessToken) {
            try {
                Http::withHeaders([
                    'apikey' => $supabaseAnonKey,
                    'Authorization' => 'Bearer ' . $accessToken,
                ])->post($supabaseUrl . '/auth/v1/logout');
            } catch (\Exception $e) {
                Log::error('Supabase logout API error: ' . $e->getMessage());
            }
        }

        $request->session()->forget(['supabase_user', 'supabase_access_token', 'supabase_refresh_token', 'supabase_token_expires_at']);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Successfully logged out.');
    }
}
