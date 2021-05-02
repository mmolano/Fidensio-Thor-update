<?php

namespace App\Providers;

use App\Services\Pressing\Pressing;
use App\Services\Pressing\PressingFake;
use App\Services\Stripe\Stripe;
use App\Services\Stripe\StripeFake;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (env('APP_ENV') === 'testing') {
            $this->app->singleton(StripeFake::class);
            $this->app->alias(StripeFake::class, 'stripe');

            $this->app->singleton(PressingFake::class);
            $this->app->alias(PressingFake::class, 'pressing');
        } else {
            $this->app->singleton(Stripe::class);
            $this->app->alias(Stripe::class, 'stripe');

            $this->app->singleton(Pressing::class);
            $this->app->alias(Pressing::class, 'pressing');
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
