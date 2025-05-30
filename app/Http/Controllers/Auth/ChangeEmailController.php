<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChangeEmailController extends Controller
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

        return view('auth.change-email', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_email' => ['required', 'email'],
            'new_email' => ['required', 'email', 'different:current_email', 'confirmed'],
            'current_password' => ['required'],
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

            // Update email in Supabase Auth
            $response = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key')
            ])->put(config('services.supabase.url') . '/auth/v1/admin/users/' . $user->id, [
                'email' => $request->new_email
            ]);

            if ($response->successful()) {
                // Update email in Laravel's auth
                $user->email = $request->new_email;
                $user->save();

                return redirect()->route('profile.show')->with('success', 'Email updated successfully.');
            } else {
                Log::error('Failed to update email in Supabase: ' . $response->body());
                return redirect()->back()->with('error', 'Failed to update email. Please try again.');
            }
        } catch (\Exception $e) {
            Log::error('Error updating email: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating your email.');
        }
    }
}
