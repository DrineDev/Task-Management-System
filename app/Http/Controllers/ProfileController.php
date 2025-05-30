<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Show the user profile page
     */
    public function showProfile()
    {
        $user = Auth::user();
        return view('profile.profile', compact('user'));
    }

    /**
     * Update the user's profile information
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        // Add profile update logic here
        return redirect()->route('profile.show')->with('success', 'Profile updated successfully');
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
}
