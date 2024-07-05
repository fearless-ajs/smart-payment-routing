<?php

namespace Fearless\SmartPaymentRouting\core\contract;

interface PaymentProcessor
{
    public function charge($amount, $currency, $details);
}