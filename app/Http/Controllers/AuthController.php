<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function supabaseCallback(Request $request)
    {
        return view('auth.supabase_callback');
    }

    public function supabaseLogin(Request $request)
    {
        $userData = $request->input('user');
        $email = $userData['email'] ?? null;
        $id = $userData['id'] ?? null;
        $fullName = $userData['user_metadata']['full_name'] ?? 'Supabase User';

        if (!$email || !$id) {
            return response()->json(['error' => 'Missing email or ID'], 422);
        }

        $localUser = User::where('email', $email)->first();

        if ($localUser) {
            $localUser->name = $fullName;
            $localUser->save();
        } else {
            $localUser = User::create([
                'id' => $id,
                'email' => $email,
                'name' => $fullName,
            ]);
        }

        Auth::login($localUser);

        return redirect('/dashboard');
    }
}
