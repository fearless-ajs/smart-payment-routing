<?php

namespace Fearless\SmartPaymentRouting\core\contract;

interface PaymentProcessor
{
    public function createPayment($user_email, $amount, $currency);
}