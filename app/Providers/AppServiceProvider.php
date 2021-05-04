<?php

namespace App\Providers;

use App\Services\Crisp\Crisp;
use App\Services\Crisp\CrispFake;
use App\Services\MailJet\MailJet;
use App\Services\MailJet\MailJetFake;
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

            $this->app->singleton(CrispFake::class);
            $this->app->alias(CrispFake::class, 'crisp');

            $this->app->singleton(MailJetFake::class);
            $this->app->alias(MailJetFake::class, 'mailjet');

            $this->app->singleton(PressingFake::class);
            $this->app->alias(PressingFake::class, 'pressing');
        } else {
            $this->app->singleton(Stripe::class);
            $this->app->alias(Stripe::class, 'stripe');

            $this->app->singleton(Crisp::class);
            $this->app->alias(Crisp::class, 'crisp');

            $this->app->singleton(MailJet::class);
            $this->app->alias(MailJet::class, 'mailjet');

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
