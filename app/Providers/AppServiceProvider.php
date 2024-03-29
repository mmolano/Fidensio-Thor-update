<?php

namespace App\Providers;

use App\Services\MailJet\MailJet;
use App\Services\MailJet\MailJetFake;
use App\Services\Pressing\Pressing;
use App\Services\Pressing\PressingFake;
use App\Services\Stripe\Stripe;
use App\Services\Stripe\StripeFake;
use Illuminate\Support\Facades\App;
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
        if (App::environment('testing')) {
            $this->app->singleton(MailJetFake::class);
            $this->app->alias(MailJetFake::class, 'mailjet');
        } else {
            $this->app->singleton(MailJet::class);
            $this->app->alias(MailJet::class, 'mailjet');
        }

        $this->app->singleton(Pressing::class);
        $this->app->alias(Pressing::class, 'pressing');
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
