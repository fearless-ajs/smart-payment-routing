<?php

namespace Fearless\SmartPaymentRouting\providers;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = 'Fearless\SmartPaymentRouting\Http\Controllers';

    public function map(): void
    {
        Route::namespace($this->namespace)
            ->group(__DIR__ . '/../../routes/web.php');
    }
}