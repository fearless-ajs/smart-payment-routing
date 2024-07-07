<?php

use Fearless\SmartPaymentRouting\Http\Controllers\BanksController;
use Illuminate\Support\Facades\Route;

Route::get(config('smart-payment-routing.routes.banks'), [BanksController::class, 'getBanks']);
Route::post(config('smart-payment-routing.routes.resolve-account-numbers'), [BanksController::class, 'resolveAccountNumber']);