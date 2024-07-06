<?php

use Fearless\SmartPaymentRouting\Http\Controllers\BanksController;
use Illuminate\Support\Facades\Route;

Route::get(config('smart-payment-routing.routes.banks'), [BanksController::class, 'getBanks']);