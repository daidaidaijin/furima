<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// トップページ → ログイン前の商品一覧
Route::get('/', function () {
    return view('product_list_guest');
});

// ログアウト
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

// ログイン後の商品一覧（認証必須）
Route::get('/products', function () {
    return view('product_list');
})->middleware('auth');
