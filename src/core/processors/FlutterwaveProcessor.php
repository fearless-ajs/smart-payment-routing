<?php

namespace Fearless\SmartPaymentRouting\core\processors;

use Fearless\SmartPaymentRouting\core\contract\PaymentProcessor;
use MusahMusah\LaravelMultipaymentGateways\Facades\Flutterwave;
use MusahMusah\LaravelMultipaymentGateways\Facades\Paystack;

class FlutterwaveProcessor implements PaymentProcessor
{
    public function createPayment($user_email, $amount, $currency)
    {
        $fields = [
            'tx_ref' => $this->generateTxRef(),
            'amount' => $amount,
            'currency' => $currency,
            'redirect_url' => getenv('FLUTTERWAVE_REDIRECT_URL'),
            'customer' => [
                'email' => $user_email,
            ],
            'customizations' => [
                'title' => 'Sample Payments',
                'logo' => 'http://www.piedpiper.com/app/themes/joystick-v27/images/logo.png'
            ]
        ];

        // Implement flutterwave charging logic
        return Flutterwave::httpClient()->post('v3/payments', $fields);
    }

    private function generateTxRef(): string  {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $txRef = '';
        for ($i = 0; $i < 18; $i++) {
            $txRef .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $txRef;
    }

}