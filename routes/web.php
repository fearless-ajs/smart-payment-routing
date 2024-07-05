<?php

use Fearless\SmartPaymentRouting\Http\Controllers\HelloController;
use Illuminate\Support\Facades\Route;

Route::get(config('smart-payment-routing.route'), [HelloController::class, 'home']);