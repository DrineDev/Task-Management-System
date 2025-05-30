<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChangePasswordController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $profile = null;

        try {
            $response = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key')
            ])->get(config('services.supabase.url') . '/rest/v1/user_profiles', [
                'id' => 'eq.' . $user->id
            ]);

            if ($response->successful() && !empty($response->json())) {
                $profile = $response->json()[0];
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch user profile: ' . $e->getMessage());
        }

        return view('auth.change-password', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();

        try {
            // First verify the password with Supabase
            $verifyResponse = Http::withHeaders([
                'apikey' => config('services.supabase.anon_key'),
                'Content-Type' => 'application/json',
            ])->post(config('services.supabase.url') . '/auth/v1/token?grant_type=password', [
                'email' => $user->email,
                'password' => $request->current_password,
            ]);

            if (!$verifyResponse->successful()) {
                return redirect()->back()->withErrors(['current_password' => 'The provided password is incorrect.']);
            }

            // Update password in Supabase Auth
            $response = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key')
            ])->put(config('services.supabase.url') . '/auth/v1/admin/users/' . $user->id, [
                'password' => $request->password
            ]);

            if ($response->successful()) {
                return redirect()->route('profile.show')->with('success', 'Password updated successfully.');
            } else {
                Log::error('Failed to update password in Supabase: ' . $response->body());
                return redirect()->back()->with('error', 'Failed to update password. Please try again.');
            }
        } catch (\Exception $e) {
            Log::error('Error updating password: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating your password.');
        }
    }
}
