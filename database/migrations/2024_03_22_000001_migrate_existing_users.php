<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MigrateExistingUsers extends Migration
{
    public function up()
    {
        try {
            // Get all users from auth.users
            $users = DB::table('auth.users')->get();
            
            $supabaseUrl = config('services.supabase.url');
            $supabaseKey = config('services.supabase.key');

            if (!$supabaseUrl || !$supabaseKey) {
                throw new Exception('Supabase credentials missing');
            }

            foreach ($users as $user) {
                // Check if profile already exists
                $existingProfile = DB::table('user_profiles')
                    ->where('id', $user->id)
                    ->first();

                if (!$existingProfile) {
                    // Create profile in user_profiles table
                    $response = Http::withHeaders([
                        'apikey' => $supabaseKey,
                        'Authorization' => 'Bearer ' . $supabaseKey,
                        'Content-Type' => 'application/json',
                    ])->post($supabaseUrl . '/rest/v1/user_profiles', [
                        'id' => $user->id,
                        'name' => $user->raw_user_meta_data->name ?? $user->email,
                        'email' => $user->email,
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
                    ]);

                    if (!$response->successful()) {
                        Log::error('Failed to migrate user profile', [
                            'user_id' => $user->id,
                            'email' => $user->email,
                            'response' => $response->json()
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error during user migration: ' . $e->getMessage());
            throw $e;
        }
    }

    public function down()
    {
        // No need for down migration as we don't want to delete user profiles
    }
} 