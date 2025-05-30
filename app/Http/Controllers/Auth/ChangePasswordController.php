<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ChangePasswordController extends Controller {

    public function showChangePassword() {
        $user = Auth::user();
        return view('auth.change-password', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        try {
            $response = Http::withHeaders([
                'apikey' => config('services.supabase.anon_key'),
                'Authorization' => 'Bearer ' . session('supabase_token'),
                'Content-Type' => 'application/json',
            ])->put(config('services.supabase.url') . '/auth/v1/user', [
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                return redirect()->route('profile.show')
                    ->with('success', 'Password updated successfully.');
            }

            Log::error('Failed to update password in Supabase', [
                'status' => $response->status(),
                'response' => $response->json()
            ]);

            return back()->withErrors(['password' => 'Failed to update password. Please try again.']);
        } catch (\Exception $e) {
            Log::error('Exception while updating password', [
                'message' => $e->getMessage()
            ]);

            return back()->withErrors(['password' => 'An error occurred. Please try again later.']);
        }
    }
}
