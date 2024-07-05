<?php

namespace Fearless\SmartPaymentRouting\core\processors;

use Fearless\SmartPaymentRouting\core\contract\PaymentProcessor;

class PaystackProcessor implements PaymentProcessor
{
    public function charge($amount, $currency, $details)
    {
        // Implement Paystack charging logic
    }
}