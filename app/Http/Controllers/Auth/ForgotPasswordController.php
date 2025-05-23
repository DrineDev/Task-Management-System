<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        try {
            $response = Http::withHeaders([
                'apikey' => config('services.supabase.anon_key'),
                'Content-Type' => 'application/json',
            ])->post(config('services.supabase.url') . '/auth/v1/recover', [
                'email' => $request->email,
            ]);

            if ($response->successful()) {
                return back()->with('status', 'Password reset link has been sent to your email address.');
            }

            return back()->withErrors(['email' => 'Unable to send password reset link. Please try again.']);
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'An error occurred. Please try again later.']);
        }
    }
} 