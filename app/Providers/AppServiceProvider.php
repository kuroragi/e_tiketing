<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\Ticket;
use App\Policies\TicketPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

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
        // Gunakan Bootstrap 5 untuk pagination
        Paginator::useBootstrapFive();

        // Daftarkan TicketPolicy untuk model Ticket
        Gate::policy(Ticket::class, TicketPolicy::class);

        // Alias Setting agar bisa digunakan di Blade tanpa namespace lengkap
        if (!class_exists('Setting')) {
            class_alias(\App\Models\Setting::class, 'Setting');
        }

        // Konfigurasi API rate limiter
        RateLimiter::for('api', function (Request $request) {
            $limit = 30;
            try {
                $limit = (int) Setting::get('api_rate_limit', 30);
            } catch (\Throwable $e) {
                // Jika tabel settings belum ada (sebelum migrasi)
            }
            return Limit::perMinute($limit)->by($request->header('X-API-Key') ?: $request->ip());
        });
    }
}
