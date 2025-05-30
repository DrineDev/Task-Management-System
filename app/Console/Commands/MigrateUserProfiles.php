<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MigrateUserProfiles extends Command
{
    protected $signature = 'users:migrate-profiles';
    protected $description = 'Migrate existing users to the new user_profiles table';

    public function handle()
    {
        $this->info('Starting user profile migration...');

        try {
            // Get all users from auth.users
            $users = DB::table('auth.users')->get();
            
            $supabaseUrl = config('services.supabase.url');
            $supabaseKey = config('services.supabase.key');

            if (!$supabaseUrl || !$supabaseKey) {
                throw new Exception('Supabase credentials missing');
            }

            $bar = $this->output->createProgressBar(count($users));
            $bar->start();

            foreach ($users as $user) {
                // Log raw_user_meta_data for debugging
                Log::info('User raw_user_meta_data', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'raw_user_meta_data' => $user->raw_user_meta_data
                ]);

                // Check if profile already exists
                $existingProfile = DB::table('user_profiles')
                    ->where('id', $user->id)
                    ->first();

                if (!$existingProfile) {
                    // Extract name from user metadata
                    $meta = $user->raw_user_meta_data;
                    $name = null;
                    if (is_object($meta)) {
                        $name = $meta->name ?? $meta->full_name ?? null;
                    } elseif (is_array($meta)) {
                        $name = $meta['name'] ?? $meta['full_name'] ?? null;
                    }
                    if (!$name) {
                        $name = $user->email;
                    }

                    // Create profile in user_profiles table
                    $response = Http::withHeaders([
                        'apikey' => $supabaseKey,
                        'Authorization' => 'Bearer ' . $supabaseKey,
                        'Content-Type' => 'application/json',
                    ])->post($supabaseUrl . '/rest/v1/user_profiles', [
                        'id' => $user->id,
                        'name' => $name,
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
                    ]);

                    if (!$response->successful()) {
                        $this->error("Failed to migrate user {$user->email}");
                        Log::error('Failed to migrate user profile', [
                            'user_id' => $user->id,
                            'email' => $user->email,
                            'response' => $response->json()
                        ]);
                    } else {
                        $this->info("Successfully migrated user {$user->email}");
                    }
                } else {
                    $this->info("User {$user->email} already has a profile");
                }

                $bar->advance();
            }

            $bar->finish();
            $this->newLine();
            $this->info('User profile migration completed!');

        } catch (\Exception $e) {
            $this->error('Error during migration: ' . $e->getMessage());
            Log::error('Error during user migration: ' . $e->getMessage());
        }
    }
} 