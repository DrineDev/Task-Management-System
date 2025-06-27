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

        // Get the full application URL including subdirectory
        $appUrl = $this->getFullAppUrl();

        Log::info('Starting OAuth redirect', [
            'provider' => $provider,
            'supabase_url' => $supabaseUrl,
            'app_url' => $appUrl,
            'config_app_url' => config('app.url'),
            'request_url' => request()->url(),
            'request_root' => request()->root(),
            'request_host' => request()->getHost(),
            'request_port' => request()->getPort(),
            'request_protocol' => request()->secure() ? 'https' : 'http',
            'script_name' => request()->server('SCRIPT_NAME'),
            'request_uri' => request()->server('REQUEST_URI')
        ]);

        $redirectUrl = $appUrl . '/auth/supabase_callback';

        $authUrl = $supabaseUrl . '/auth/v1/authorize?' . http_build_query([
            'provider' => $provider,
            'redirect_to' => $redirectUrl,
            'scopes' => $provider === 'google' ? 'openid email profile' : 'email',
        ]);

        Log::info('Generated auth URL:', [
            'url' => $authUrl,
            'redirect_to' => $redirectUrl
        ]);

        return redirect($authUrl);
    }

    /**
     * Get the full application URL including subdirectory for XAMPP setups
     */
    private function getFullAppUrl()
    {
        // Use the APP_URL environment variable for deployment flexibility
        return config('app.url');
    }

    public function supabaseCallback(Request $request)
    {
        Log::info('Received callback request', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'headers' => $request->headers->all(),
            'query' => $request->query(),
            'all_input' => $request->all(),
            'is_secure' => $request->secure()
        ]);

        if ($request->isMethod('post')) {
            // Handle POST request from the callback view
            $accessToken = $request->input('access_token');
            $refreshToken = $request->input('refresh_token');

            Log::info('Processing POST callback', [
                'has_access_token' => !empty($accessToken),
                'has_refresh_token' => !empty($refreshToken),
                'access_token_length' => $accessToken ? strlen($accessToken) : 0
            ]);

            if (!$accessToken) {
                Log::warning('No access token in POST request');
                return response()->json(['success' => false, 'message' => 'No access token provided']);
            }

            try {
                // Get user info from Supabase using the access token
                $supabaseUrl = config('services.supabase.url');
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                    'apikey' => config('services.supabase.anon_key'),
                ])->get($supabaseUrl . '/auth/v1/user');

                Log::info('Supabase user info response', [
                    'status' => $response->status(),
                    'body' => $response->json()
                ]);

                if ($response->successful()) {
                    $userData = $response->json();
                    $user = User::fromSupabase($userData);

                    // Store the tokens in session
                    $request->session()->put('supabase_access_token', $accessToken);
                    if ($refreshToken) {
                        $request->session()->put('supabase_refresh_token', $refreshToken);
                    }
                    $request->session()->put('supabase_token_expires_at', now()->addHour());

                    Auth::login($user);
                    $request->session()->regenerate();

                    Log::info('Successfully authenticated user', [
                        'user_id' => $user->id,
                        'email' => $user->email
                    ]);

                    return response()->json(['success' => true]);
                } else {
                    Log::error('Failed to get user info from Supabase', [
                        'status' => $response->status(),
                        'body' => $response->json()
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Error during OAuth callback', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Authentication failed']);
        }

        // Handle GET requests - check for direct token parameters or error
        if ($request->has('access_token')) {
            // Direct callback with tokens in query params
            $accessToken = $request->get('access_token');
            $refreshToken = $request->get('refresh_token');

            Log::info('Direct GET callback with tokens', [
                'has_access_token' => !empty($accessToken),
                'has_refresh_token' => !empty($refreshToken)
            ]);

            try {
                $supabaseUrl = config('services.supabase.url');
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                    'apikey' => config('services.supabase.anon_key'),
                ])->get($supabaseUrl . '/auth/v1/user');

                if ($response->successful()) {
                    $userData = $response->json();
                    $user = User::fromSupabase($userData);

                    $request->session()->put('supabase_access_token', $accessToken);
                    if ($refreshToken) {
                        $request->session()->put('supabase_refresh_token', $refreshToken);
                    }
                    $request->session()->put('supabase_token_expires_at', now()->addHour());

                    Auth::login($user);
                    $request->session()->regenerate();

                    return redirect()->route('dashboard')->with('success', 'Successfully logged in with ' . ucfirst($request->get('provider', 'OAuth')));
                }
            } catch (\Exception $e) {
                Log::error('Error processing direct callback', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }

            return redirect()->route('login')->with('error', 'Authentication failed');
        }

        // Check for OAuth errors
        if ($request->has('error')) {
            $error = $request->get('error');
            $errorDescription = $request->get('error_description', 'Authentication failed');

            Log::warning('OAuth error received', [
                'error' => $error,
                'error_description' => $errorDescription
            ]);

            return redirect()->route('login')->with('error', $errorDescription);
        }

        // For GET requests without direct tokens, return the callback view that will handle fragment tokens
        return view('auth.supabase_callback');
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
