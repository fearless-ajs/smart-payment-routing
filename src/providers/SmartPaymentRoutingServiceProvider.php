<?php

namespace Fearless\SmartPaymentRouting\providers;

use Fearless\SmartPaymentRouting\core\PaymentRoutingService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class SmartPaymentRoutingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // load migrations from migrations path
        $this->loadMigrationsFrom($this->basePath('database/migrations'));

        // Make migrations publishable and allow users overwrite it
        $this->publishes([
            $this->basePath('database/migrations') => database_path('migrations'),
        ], 'smart-payment-routing-migrations');

        // publish the configuration file
        $this->publishes([
            $this->basePath('config/smart-payment-routing.php') => base_path('config/smart-payment-routing.php'),
        ], 'smart-payment-routing-config');
    }

    public function register(): void
    {
        $this->app->bind('smart-payment-routing', function () {
            return new PaymentRoutingService;
        });

        //load configuration file
        $this->mergeConfigFrom($this->basePath('config/smart-payment-routing.php'), 'smart-payment-routing');
    }

    protected function basePath($path = ''): string
    {
        return __DIR__ . '/../../' . $path;
    }
}