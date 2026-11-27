<?php

namespace App\Http\Controllers;

class CheckoutController extends Controller
{
    public function success()
    {
        return view('success');
    }

    public function cancel()
    {
        return view('cancel');
    }
}