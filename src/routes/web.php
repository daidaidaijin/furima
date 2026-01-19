<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ItemController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ShippingAddressController;

/*
|--------------------------------------------------------------------------
| Public（ゲストOK）
|--------------------------------------------------------------------------
*/

// トップ（商品一覧）
Route::get('/', [ItemController::class, 'index'])->name('items.index');

// 商品詳細（ゲストOK）
Route::get('/items/{item}', [ItemController::class, 'show'])
    ->name('items.show')
    ->whereNumber('item');

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/

// ログアウト
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// デバッグ（不要なら消してOK）
Route::get('/debug-auth', function () {
    $u = auth()->user();

    return [
        'auth_check' => auth()->check(),
        'email' => $u?->email,
        'verified_at' => $u?->email_verified_at,
        'has_verified_email' => $u?->hasVerifiedEmail(),
    ];
})->middleware('web');

/*
|--------------------------------------------------------------------------
| Verified（メール認証済みのみ）
|--------------------------------------------------------------------------
*/

Route::get('/home', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Auth only（ログインしていればOK）
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // マイページ（?tab=sell / ?tab=buy）
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage');

    // マイリスト（いいね一覧など想定）
    Route::get('/mylist', [ItemController::class, 'mylist'])->name('items.mylist');

    // 出品
    Route::get('/items/sell', [ItemController::class, 'create'])->name('items.sell');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');

    // いいね
    Route::post('/items/{item}/like', [LikeController::class, 'toggle'])->name('items.like');

    // コメント投稿
    Route::post('/items/{item}/comments', [CommentController::class, 'store'])->name('items.comments.store');

    // 購入画面
    Route::get('/purchase/{item}', [PurchaseController::class, 'show'])->name('purchase.show');

    // Stripeへ（購入ボタン）
    Route::post('/purchase/{item}/checkout', [PurchaseController::class, 'checkout'])->name('purchase.checkout');

    // Stripe成功（注文確定&sold）
    Route::get('/purchase/success/{item}', [PurchaseController::class, 'success'])->name('purchase.success');

    // 住所変更
    Route::get('/purchase/{item}/address/edit', [ShippingAddressController::class, 'edit'])->name('purchase.address.edit');
    Route::post('/purchase/{item}/address', [ShippingAddressController::class, 'update'])->name('purchase.address.update');
});
