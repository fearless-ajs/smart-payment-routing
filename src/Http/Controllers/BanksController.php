<?php

namespace Fearless\SmartPaymentRouting\Http\Controllers;

use Fearless\SmartPaymentRouting\Facades\SmartPaymentRouting;
use Illuminate\Routing\Controller;
use MusahMusah\LaravelMultipaymentGateways\Contracts\PaystackContract;

class BanksController extends Controller
{
    private readonly PaystackContract $paystack;
    public function __construct(PaystackContract $paystack)
    {
        $this->paystack = $paystack;
    }

    public function getBanks(): array
    {
        return $this->paystack->getBanks();
    }
}