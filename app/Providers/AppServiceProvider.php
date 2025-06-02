<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share user profile data with views that extend the app layout
        View::composer(['layouts.app', 'dashboard.*', 'profile.*'], function ($view) {
            if (auth()->check()) {
                $user = auth()->user();
                \Log::info('Fetching profile for user', ['user_id' => $user->id]);
                
                $profileResponse = Http::withHeaders([
                    'apikey' => config('services.supabase.key'),
                    'Authorization' => 'Bearer ' . config('services.supabase.key'),
                    'Content-Type' => 'application/json',
                ])->get(config('services.supabase.url') . '/rest/v1/user_profiles', [
                    'id' => 'eq.' . $user->id
                ]);

                \Log::info('Profile response', [
                    'status' => $profileResponse->status(),
                    'body' => $profileResponse->body()
                ]);

                $profile = $profileResponse->successful() && !empty($profileResponse->json()) ? $profileResponse->json()[0] : null;

                \Log::info('Profile data', ['profile' => $profile]);

                $view->with([
                    'user' => $user,
                    'profile' => $profile
                ]);
            }
        });
    }
}
