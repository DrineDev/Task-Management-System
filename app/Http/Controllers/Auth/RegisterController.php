<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    /**
     * Display the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            Log::warning('Registration validation failed', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->except('password')
            ]);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $emailExists = DB::table('auth.users')->where('email', $request->email)->exists();

        if ($emailExists) {
            Log::warning('Registration attempt with existing email', ['email' => $request->email]);
            return redirect()->back()
                ->withErrors(['email' => 'The email has already been taken.'])
                ->withInput();
        }

        $supabaseUrl = config('services.supabase.url');
        $supabaseAnonKey = config('services.supabase.anon_key');
        $supabaseKey = config('services.supabase.key');

        if (!$supabaseUrl || !$supabaseAnonKey || !$supabaseKey) {
            Log::error('Supabase credentials missing in config/services.php or .env file.');
            return redirect()->back()->with('error', 'Supabase credentials are not configured correctly. Please contact support.')->withInput();
        }

        $authUrl = $supabaseUrl . '/auth/v1/signup';

        try {
            // Register the user in Supabase Auth using anon key
            $response = Http::withHeaders([
                'apikey' => $supabaseAnonKey,
                'Content-Type' => 'application/json',
            ])->post($authUrl, [
                'email' => $request->email,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                $userData = $response->json();
                
                // Create user profile in Supabase using service role key
                $profileResponse = Http::withHeaders([
                    'apikey' => $supabaseKey,
                    'Authorization' => 'Bearer ' . $supabaseKey,
                    'Content-Type' => 'application/json',
                ])->post($supabaseUrl . '/rest/v1/user_profiles', [
                    'id' => $userData['user']['id'],
                    'name' => $request->name,
                ]);

                if (!$profileResponse->successful()) {
                    Log::error('Failed to create user profile in Supabase', [
                        'status' => $profileResponse->status(),
                        'response' => $profileResponse->json(),
                        'user_id' => $userData['user']['id']
                    ]);
                    return redirect()->back()->with('error', 'Account created but profile setup failed. Please contact support.')->withInput();
                }

                Log::info('User registration successful', [
                    'user_id' => $userData['user']['id'],
                    'email' => $request->email
                ]);
                return redirect('/')->with('success', 'Registration successful! Please check your email if confirmation is required.');
            } else {
                $errorData = $response->json();
                $errorMessage = $errorData['msg'] ?? ($errorData['message'] ?? 'An unknown error occurred during registration.');
                Log::warning('Supabase registration failed', [
                    'email' => $request->email,
                    'error' => $errorMessage,
                    'response' => $errorData
                ]);
                return redirect()->back()->with('error', 'Registration failed: ' . $errorMessage)->withInput();
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Supabase connection error during registration', [
                'error' => $e->getMessage(),
                'email' => $request->email
            ]);
            return redirect()->back()->with('error', 'Could not connect to authentication service. Please try again later.')->withInput();
        } catch (\Exception $e) {
            Log::error('General error during registration', [
                'error' => $e->getMessage(),
                'email' => $request->email,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'An unexpected error occurred. Please try again.')->withInput();
        }
    }
}
