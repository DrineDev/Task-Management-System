<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Show the user profile page
     */
    public function show()
    {
        $user = auth()->user();
        $profile = null;

        try {
            Log::info('Fetching profile for user', [
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);

            // Use the correct endpoint for public schema
            $response = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
                'Content-Type' => 'application/json',
                'Prefer' => 'return=representation'
            ])->get(config('services.supabase.url') . '/rest/v1/user_profiles', [
                'id' => 'eq.' . $user->id,
                'select' => 'id,name,avatar_url'
            ]);

            Log::info('Supabase response', [
                'status' => $response->status(),
                'body' => $response->json(),
                'url' => config('services.supabase.url') . '/rest/v1/user_profiles'
            ]);

            if ($response->successful() && !empty($response->json())) {
                $profile = $response->json()[0];
                Log::info('Profile found', ['profile' => $profile]);
            } else {
                Log::warning('No profile found or unsuccessful response', [
                    'status' => $response->status(),
                    'body' => $response->json()
                ]);

                // Try to create the profile if it doesn't exist
                $createResponse = Http::withHeaders([
                    'apikey' => config('services.supabase.key'),
                    'Authorization' => 'Bearer ' . config('services.supabase.key'),
                    'Content-Type' => 'application/json',
                    'Prefer' => 'return=representation'
                ])->post(config('services.supabase.url') . '/rest/v1/user_profiles', [
                    'id' => $user->id,
                    'name' => $user->email
                ]);

                if ($createResponse->successful()) {
                    $profile = $createResponse->json()[0];
                    Log::info('Profile created successfully', ['profile' => $profile]);
                } else {
                    Log::error('Failed to create profile', [
                        'status' => $createResponse->status(),
                        'body' => $createResponse->json()
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch user profile: ' . $e->getMessage(), [
                'exception' => $e
            ]);
        }

        return view('profile.profile', compact('user', 'profile'));
    }

    /**
     * Update the user's profile information
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'max:2048'], // max 2MB
        ]);

        try {
            $user = Auth::user();
            
            // Get current profile data
            $currentProfileResponse = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
                'Content-Type' => 'application/json',
            ])->get(config('services.supabase.url') . '/rest/v1/user_profiles', [
                'id' => 'eq.' . $user->id
            ]);

            if (!$currentProfileResponse->successful() || empty($currentProfileResponse->json())) {
                return back()->withErrors(['profile' => 'Failed to fetch current profile. Please try again.']);
            }

            $currentProfile = $currentProfileResponse->json()[0];
            $updateData = [];

            // Handle name update
            if ($request->filled('name') && $request->name !== $currentProfile['name']) {
                $updateData['name'] = $request->name;
            }

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $fileName = $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                
                // Upload to Supabase Storage
                $uploadResponse = Http::withHeaders([
                    'apikey' => config('services.supabase.key'),
                    'Authorization' => 'Bearer ' . config('services.supabase.key'),
                ])->attach(
                    'file', file_get_contents($file), $fileName, [
                        'Content-Type' => $file->getMimeType()
                    ]
                )->post(config('services.supabase.url') . '/storage/v1/object/profile-pictures/' . $fileName);

                if ($uploadResponse->successful()) {
                    // Get the public URL for the uploaded file
                    $publicUrl = config('services.supabase.url') . '/storage/v1/object/public/profile-pictures/' . $fileName;
                    $updateData['avatar_url'] = $publicUrl;

                    // Delete old avatar if it exists
                    if (!empty($currentProfile['avatar_url'])) {
                        $oldFileName = basename($currentProfile['avatar_url']);
                        Http::withHeaders([
                            'apikey' => config('services.supabase.key'),
                            'Authorization' => 'Bearer ' . config('services.supabase.key'),
                        ])->delete(config('services.supabase.url') . '/storage/v1/object/profile-pictures/' . $oldFileName);
                    }
                } else {
                    Log::error('Failed to upload avatar to Supabase', [
                        'status' => $uploadResponse->status(),
                        'response' => $uploadResponse->json()
                    ]);
                    return back()->withErrors(['avatar' => 'Failed to upload profile picture. Please try again.']);
                }
            }

            // If nothing has changed, return success
            if (empty($updateData)) {
                return redirect()->route('profile.show')
                    ->with('success', 'No changes were made.');
            }

            // Update profile in Supabase
            $response = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
                'Content-Type' => 'application/json',
                'Prefer' => 'return=representation'
            ])->patch(config('services.supabase.url') . '/rest/v1/user_profiles?id=eq.' . $user->id, $updateData);

            if ($response->successful()) {
                return redirect()->route('profile.show')
                    ->with('success', 'Profile updated successfully.');
            }

            Log::error('Failed to update profile in Supabase', [
                'status' => $response->status(),
                'response' => $response->json(),
                'updateData' => $updateData
            ]);

            return back()->withErrors(['profile' => 'Failed to update profile. Please try again.']);
        } catch (\Exception $e) {
            Log::error('Exception while updating profile', [
                'message' => $e->getMessage()
            ]);

            return back()->withErrors(['profile' => 'An error occurred. Please try again later.']);
        }
    }

    /**
     * Show change password form (if you want to add this functionality)
     */
    public function showChangePassword()
    {
        return view('profile.change-password');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        // Add password update logic here
        return redirect()->route('profile.show')->with('success', 'Password updated successfully');
    }

    /**
     * Handle user logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully.');
    }

    public function index()
    {
        return view('profile.profile');
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'current_email' => ['required', 'email', 'current_password'],
            'new_email' => ['required', 'email', 'different:current_email', 'confirmed'],
        ]);

        $user = auth()->user();

        try {
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
                return back()->withErrors(['email' => 'Failed to update email. Please try again.']);
            }
        } catch (\Exception $e) {
            Log::error('Failed to update email: ' . $e->getMessage());
            return back()->withErrors(['email' => 'An error occurred while updating your email.']);
        }
    }

    /**
     * Delete the user's account
     */
    public function deleteAccount(Request $request)
    {
        try {
            $user = Auth::user();

            // Delete user profile from Supabase
            $profileResponse = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
                'Content-Type' => 'application/json',
            ])->delete(config('services.supabase.url') . '/rest/v1/user_profiles?id=eq.' . $user->id);

            if (!$profileResponse->successful()) {
                Log::error('Failed to delete user profile from Supabase', [
                    'status' => $profileResponse->status(),
                    'response' => $profileResponse->json()
                ]);
            }

            // Delete user from Supabase Auth
            $authResponse = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
            ])->delete(config('services.supabase.url') . '/auth/v1/admin/users/' . $user->id);

            if (!$authResponse->successful()) {
                Log::error('Failed to delete user from Supabase Auth', [
                    'status' => $authResponse->status(),
                    'response' => $authResponse->json()
                ]);
            }

            // Delete user from Laravel
            $user->delete();

            // Logout the user
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('success', 'Your account has been deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete account: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return back()->withErrors(['account' => 'Failed to delete account. Please try again later.']);
        }
    }
}
