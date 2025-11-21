<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductlistController extends Controller {
    public function productlist() {
        return view('product_list');
    }
}