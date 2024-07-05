<?php

namespace Fearless\SmartPaymentRouting\Facades;

use Illuminate\Support\Facades\Facade;

class SmartPaymentRouting extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'smart-payment-routing';
    }
}