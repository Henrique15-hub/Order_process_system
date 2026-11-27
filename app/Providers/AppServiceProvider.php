<?php

namespace App\Providers;

use App\Integrations\Stripe\Clients\InternalStripeClient;
use App\Integrations\Stripe\Contracts\StripeClientInterface;
use Illuminate\Support\ServiceProvider;
use Stripe\StripeClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            StripeClient::class,
            function () {
                return new StripeClient(config('services.stripe.secret'));
            }
        );

        // dependency invertion
        $this->app->bind(
            StripeClientInterface::class,
            InternalStripeClient::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

}
