<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bisa tambahkan binding atau helper di sini jika perlu
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Definisi Gate untuk otorisasi peran
        Gate::define('manage-users', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('approve-news', function ($user) {
            return in_array(strtolower($user->role), ['editor', 'admin']);
        });

        Gate::define('create-news', function ($user) {
            return in_array($user->role, ['wartawan', 'admin']);
        });
    }
}
