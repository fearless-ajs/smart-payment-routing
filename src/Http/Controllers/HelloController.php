<?php

namespace Fearless\SmartPaymentRouting\Http\Controllers;

use Fearless\SmartPaymentRouting\Facades\SmartPaymentRouting;
use Illuminate\Routing\Controller;

class HelloController
{
//    public function index()
//    {
//        return view('smart-payment-routing::home', [
//            'message'   => SmartPaymentRouting::hello()
//        ]);
//    }

    public function home(): string
    {
        return 'Hello World!';
    }
}