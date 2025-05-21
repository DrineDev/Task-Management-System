<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller {

    public function showRegistrationForm() {
        return view('auth.register');
    }

    public function register(Request $request) {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            DB::connection('supabase')->table('users')->insert([
                'id' => Str::uuid(),
                'email' => $request->email,
                'password_hash' => Hash::make($request->password),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect('/')->with('success', 'Registration successful!');
        } catch (\Exception $e) {
            return back()->with('error', 'Registration fialed: ' . $e->getMessage());
        }
    }
}
