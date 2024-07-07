<?php

namespace Fearless\SmartPaymentRouting\core\processors;

use Fearless\SmartPaymentRouting\core\contract\PaymentProcessor;
use MusahMusah\LaravelMultipaymentGateways\Facades\Paystack;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

class PaystackProcessor implements PaymentProcessor
{
    public function createPayment($user_email, $amount, $currency)
    {
        $fields = [
            'email' => $user_email,
            'amount' => $amount * 100,
            'currency' => strtoupper($currency),
        ];

        try {
            // Implement Paystack charging logic
            return Paystack::httpClient()->post('transaction/initialize', $fields);
        }catch (\Exception $exception){
          throw new NotAcceptableHttpException($exception->getMessage());
        }

    }
}