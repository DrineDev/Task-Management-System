<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CheckUserProfile extends Command
{
    protected $signature = 'profile:check {email?}';
    protected $description = 'Check if a user profile exists and create it if it doesn\'t';

    public function handle()
    {
        $email = $this->argument('email');
        
        if ($email) {
            $user = \App\Models\User::where('email', $email)->first();
        } else {
            $user = Auth::user();
        }

        if (!$user) {
            $this->error('User not found');
            return 1;
        }

        $this->info("Checking profile for user: {$user->email} (ID: {$user->id})");

        try {
            // Check if profile exists
            $response = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key')
            ])->get(config('services.supabase.url') . '/rest/v1/user_profiles', [
                'id' => 'eq.' . $user->id
            ]);

            if ($response->successful() && !empty($response->json())) {
                $profile = $response->json()[0];
                $this->info('Profile found:');
                $this->table(
                    ['ID', 'Name', 'Avatar URL'],
                    [[$profile['id'], $profile['name'], $profile['avatar_url'] ?? 'N/A']]
                );
            } else {
                $this->warn('No profile found. Creating one...');

                // Create profile
                $createResponse = Http::withHeaders([
                    'apikey' => config('services.supabase.key'),
                    'Authorization' => 'Bearer ' . config('services.supabase.key'),
                    'Content-Type' => 'application/json',
                ])->post(config('services.supabase.url') . '/rest/v1/user_profiles', [
                    'id' => $user->id,
                    'name' => $user->email, // Default to email as name
                ]);

                if ($createResponse->successful()) {
                    $this->info('Profile created successfully');
                } else {
                    $this->error('Failed to create profile: ' . $createResponse->body());
                }
            }
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            Log::error('Error checking/creating profile', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            return 1;
        }

        return 0;
    }
} 