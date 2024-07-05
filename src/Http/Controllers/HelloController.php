<?php

namespace Fearless\SmartPaymentRouting\Http\Controllers;

use Fearless\SmartPaymentRouting\Facades\SmartPaymentRouting;
use Illuminate\Routing\Controller;

class HelloController extends Controller
{
    public function home(): string
    {
        return hello();
    }
}