<?php

namespace Fearless\SmartPaymentRouting\Tests;

use Fearless\SmartPaymentRouting\Facades\SmartPaymentRouting;
use Fearless\SmartPaymentRouting\providers\RouteServiceProvider;
use Fearless\SmartPaymentRouting\providers\SmartPaymentRoutingServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase {

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
        ]);
    }

    protected function getPackageProviders($app): array
    {
        return [
            SmartPaymentRoutingServiceProvider::class,
            RouteServiceProvider::class
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'SmartPaymentRouting' => SmartPaymentRouting::class,
        ];
    }

}