<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function index()
    {
        return view('customer.cart');
    }
}