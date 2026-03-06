<?php

namespace App\Providers;

use App\Models\Reservation;
use App\Observers\ReservationObserver;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);

        // Registers the reservations observer (explicit Observer pattern)
        Reservation::observe(ReservationObserver::class);
    }
}
