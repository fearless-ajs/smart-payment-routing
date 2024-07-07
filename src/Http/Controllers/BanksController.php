<?php

namespace Fearless\SmartPaymentRouting\Http\Controllers;

use Fearless\SmartPaymentRouting\Facades\SmartPaymentRouting;
use Fearless\SmartPaymentRouting\Http\Requests\ResolveAccountNumberRequest;
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

    public function resolveAccountNumber(ResolveAccountNumberRequest $request)
    {
        $payload = [
            'account_number' => $request->get('account_number'),
            'bank_code' => $request->get('bank_code'),
        ];
        return $this->paystack->resolveAccountNumber($payload);
    }
}