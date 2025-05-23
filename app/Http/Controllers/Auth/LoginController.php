<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /* Show login form */
    public function showLoginForm(){
        return view('auth.login');
    }

    //1. Validate the incoming data(email and password)

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string',],
        ]);

        //Validation Failure
        if($validator->fails()){
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        //2. Get supabase credentials
        $supabaseUrl = config('services.supabase.url');
        $supabaseAnonKey = config('services.supabase.anon_key');

        if(!$supabaseUrl || !$supabaseAnonKey){
            Log::error('Supabase credentials missing for login.');
            return redirect()->back()->with('error', 'Authentication service is misconfigured. Please contact support.')->withInput();
        }

        //3. Prepare for Supabase API request
        //Endpoint for token-based login (password grant)
        $authUrl = $supabaseUrl . '/auth/v1/token?grant_type=password';

        try{
            //Make POST request
            $response = Http::withHeaders([
                'apikey' => $supabaseAnonKey,
                'Content-Type' => 'application/json',
            ])->post($authUrl, [
                'email' => $request->email,
                'password' => $request->password,
            ]);

            //4. Handle supabase API response
            if($response->successful()){
                $data = $response->json();

                //Store supabase user information and tokens in Laravel's Session
                //Will allow app to remember the user is logged in
                $request->session()->put('supabase_user', $data['user']);
                $request->session()->put('supabase_access_token', $data['access_token']);
                $request->session()->put('supabase_refresh_token', $data['refresh_token'] ?? null);
                $request->session()->put('supabase_token_expires_at', now()->addSeconds($data['expires_in']));

                $request->session()->regenerate();

                return redirect()->intended('/register')->with('success', 'Successfully Logged in!');

            } else {
                // Login failed on Supabase's side
                $errorData = $response->json();
                $errorMessage = $errorData['error_description'] ?? ($errorData['message'] ?? 'Invalid login credentials or user does not exist.');

                Log::warning('Supabase login failed for email: ' . $request->email . ' - Supabase Error: ' . $errorMessage, ['response_body' => $errorData]);
                return redirect()->back()->with('error', $errorMessage)->withInput();

            }
        } catch(\Illuminate\Http\Client\ConnectionException $e){
            Log::error('Supabase connection error during login: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Could not connect to authentication service. Please try again later.')->withInput();
        } catch(\Exception $e){
            Log::error('General error during Supabase login: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'An unexpected error occured. Please try again')->withInput();
        }
    }

    public function logout(Request $request){
        $supabaseUrl = config('services.supabase.url');
        $supabaseAnonKey = config('services.supabase.anon_key');
        $accessToken = $request->session()->get('supabase_access_token');

        //1. Tell Supabase to Invalidate the token
        if($supabaseUrl && $supabaseAnonKey && $accessToken){
            try{
                Http::withHeaders([
                    'apikey' => $supabaseAnonKey,
                    'Authorization' => 'Bearer ' . $accessToken,
                ])->post($supabaseUrl . '/auth/v1/logout');
            } catch(\Exception $e){
                Log::error('Supabase logout API error: ' . $e->getMessage());
            }
        }

        //2. Clear Supabase-related data from Laravel Session
        $request->session()->forget(['supabase_user', 'supabase_access_token', 'supabase_refresh_token', 'supabase_token_expires_at']);

        //3. Invalidate the entire Laravel session and regenerat CSRF token

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Successfully logged out.');
    }

}
