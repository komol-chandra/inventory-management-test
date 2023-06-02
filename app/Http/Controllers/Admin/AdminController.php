<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function adminHome()
    {
        $products  = Product::count('id');
        $purchases = Purchase::count('id');
        return view('backend.dashboard.main', compact('products', 'purchases'));
    }
}
