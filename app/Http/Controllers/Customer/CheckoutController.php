<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;

class CheckoutController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::where('aktif', true)->get();
        $tableNumber    = session('table_number');

        return view('customer.checkout', compact('paymentMethods', 'tableNumber'));
    }
}
