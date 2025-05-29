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

        $emailExists = DB::table('auth.users')->where('email', $request->email)->exists();

        if ($emailExists) {
            return redirect()->back()
                ->withErrors(['email' => 'The email has already been taken.'])
                ->withInput();
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $supabaseUrl = config('services.supabase.url');
        $supabaseAnonKey = config('services.supabase.anon_key');

        if (!$supabaseUrl || !$supabaseAnonKey) {
            Log::error('Supabase credentials missing in config/services.php or .env file.');
            return redirect()->back()->with('error', 'Supabase credentials are not configured correctly. Please contact support.')->withInput();
        }

        $authUrl = $supabaseUrl . '/auth/v1/signup';

        try {
            $response = Http::withHeaders([
                'apikey' => $supabaseAnonKey,
                'Content-Type' => 'application/json',
            ])->post($authUrl, [
                'email' => $request->email,
                'password' => $request->password,
                'data' => [
                    'full_name' => $request->name,
                ]
            ]);

            if ($response->successful()) {
                return redirect('/')->with('success', 'Registration successful! Please check your email if confirmation is required.');
            } else {
                $errorData = $response->json();
                $errorMessage = $errorData['msg'] ?? ($errorData['message'] ?? 'An unknown error occurred during Supabase registration.');
                Log::warning('Supabase registration failed for email: ' . $request->email . ' - Supabase Error: ' . $errorMessage, ['response_body' => $errorData]);
                return redirect()->back()->with('error', 'Registration failed: ' . $errorMessage)->withInput();
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Supabase connection error during registration: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Could not connect to authentication service. Please try again later.')->withInput();
        } catch (\Exception $e) {
            Log::error('General error during Supabase registration: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'An unexpected error occurred. Please try again.')->withInput();
        }
    }
}
