<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SupabaseLoginController extends Controller
{
    public function login(Request $request)
    {
        try {
            $accessToken = $request->input('access_token');
            $refreshToken = $request->input('refresh_token');
            $expiresAt = $request->input('expires_at');
            $expiresIn = $request->input('expires_in');

            if (!$accessToken) {
                return response()->json(['error' => 'No access token provided'], 400);
            }

            // Get user info from Supabase
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'apikey' => config('services.supabase.anon_key')
            ])->get(config('services.supabase.url') . '/auth/v1/user');

            if ($response->successful()) {
                $userData = $response->json();
                
                // Find or create user
                $user = User::firstOrNew(['id' => $userData['id']]);
                $user->email = $userData['email'];
                $user->raw_user_meta_data = $userData['user_metadata'] ?? [];
                $user->save();

                // Store the tokens in session
                session([
                    'supabase_access_token' => $accessToken,
                    'supabase_refresh_token' => $refreshToken,
                    'supabase_token_expires_at' => $expiresAt,
                    'user' => $userData
                ]);

                Auth::login($user);
                $request->session()->regenerate();

                return response()->json(['success' => true]);
            }

            Log::error('Failed to get user info from Supabase', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            return response()->json(['error' => 'Failed to get user info'], 401);
        } catch (\Exception $e) {
            Log::error('Supabase login error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }
} 