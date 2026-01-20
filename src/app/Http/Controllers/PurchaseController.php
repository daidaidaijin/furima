<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;

class PurchaseController extends Controller
{
    /**
     * 購入画面表示
     */
    public function show(Item $item)
    {
        $user = auth()->user();

        // 売却済み or 自分の商品は購入不可
        if ($item->is_sold || $item->user_id === $user->id) {
            abort(403);
        }

        // ① 登録済み配送先があれば優先
        $shipping = $user->shippingAddress;

        // ② 無ければ users テーブルの住所を仮表示
        if (!$shipping) {
            $address = trim(implode('', array_filter([
                $user->address_pref,
                $user->address_city,
                $user->address_detail,
            ])));

            $shipping = (object) [
                'postal_code' => $user->postal_code ?? null,
                'address'     => $address ?: null,
                'building'    => null,
            ];
        }

        return view('purchase.show', compact('item', 'shipping'));
    }

    /**
     * Stripe決済開始
     */
    public function checkout(PurchaseRequest $request, Item $item)
    {
        $user = auth()->user();

        // 売却済み or 自分の商品は購入不可
        if ($item->is_sold || $item->user_id === $user->id) {
            abort(403);
        }

        // FormRequestで検証済みデータ
        $validated = $request->validated();

        Stripe::setApiKey(config('services.stripe.secret'));

        $paymentTypes = $validated['payment_method'] === 'konbini'
            ? ['konbini']
            : ['card'];

        // 注文（未確定）作成
        $order = Order::create([
            'user_id'        => $user->id,
            'item_id'        => $item->id,
            'price'          => $item->price,
            'payment_method' => $validated['payment_method'],
        ]);

        $session = CheckoutSession::create([
            'mode' => 'payment',
            'payment_method_types' => $paymentTypes,
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency' => 'jpy',
                    'unit_amount' => (int) $item->price,
                    'product_data' => [
                        'name' => $item->title ?? $item->name,
                    ],
                ],
            ]],
            'success_url' => route('purchase.success', $item) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('purchase.show', $item),
        ]);

        $order->update([
            'stripe_session_id' => $session->id,
        ]);

        return redirect($session->url);
    }

    /**
     * 決済成功後
     */
    public function success(Item $item)
    {
        $user = auth()->user();

        $sessionId = request()->query('session_id');
        if (!$sessionId) {
            abort(400);
        }

        Stripe::setApiKey(config('services.stripe.secret'));
        $session = CheckoutSession::retrieve($sessionId);

        // 自分の注文か確認
        $order = Order::where('stripe_session_id', $sessionId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Stripe側の決済状態チェック
        if (($session->payment_status ?? null) !== 'paid') {
            return redirect()
                ->route('purchase.show', $item)
                ->with('message', '決済が完了していません');
        }

        // 売却確定処理（排他制御）
        DB::transaction(function () use ($item, $order) {
            $lockedItem = Item::where('id', $item->id)
                ->lockForUpdate()
                ->first();

            if ($lockedItem->is_sold) {
                return;
            }

            $lockedItem->update(['is_sold' => true]);
            $order->update(['purchased_at' => now()]);
        });

        return redirect()
            ->route('items.index')
            ->with('message', '購入が完了しました');
    }
}
