<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductlistController;

Route::get('/', [ProductlistController::class, 'productlist']);
