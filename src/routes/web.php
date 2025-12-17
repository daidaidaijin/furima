<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ItemController;

// トップページ（商品一覧・ゲスト）
Route::get('/', [ItemController::class, 'index'])->name('items.index');

// ログアウト
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

// ログイン後の商品一覧
Route::get('/products', function () {
    return view('product_list');
})->middleware('auth');

// 出品
Route::middleware('auth')->group(function () {
    Route::get('/items/sell', [ItemController::class, 'create'])->name('items.sell');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
});
Route::get('/items/{item}', [ItemController::class, 'show'])
    ->name('items.show');