<?php

namespace App\Providers;

use App\Supabase\SupabaseClient;  // Using the SupabaseClient we created
use Illuminate\Support\ServiceProvider;

class SupabaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('supabase', function ($app) {
            return new SupabaseClient();
        });

        // For dependency injection
        $this->app->bind(SupabaseClient::class, function ($app) {
            return $app->make('supabase');
        });
    }

    public function boot()
    {
        // You can add configuration validation here if needed
    }
}
