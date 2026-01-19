<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;

class PurchaseController extends Controller
{
    public function show(Item $item)
    {
        $user = auth()->user();

        if ($item->is_sold || $item->user_id === $user->id) {
            abort(403);
        }

        // ① 購入用住所があれば優先（住所変更画面で保存する想定）
        $shipping = $user->shippingAddress;

        // ② 無ければマイページ（usersテーブル）の住所を表示用に使う
        if (!$shipping) {
            $address = trim(implode('', array_filter([
                $user->address_pref,
                $user->address_city,
                $user->address_detail,
            ])));

            $shipping = (object) [
                'postal_code' => $user->postal_code ?? null,
                'address'     => $address ?: null,
                'building'    => null, // usersに無いので一旦null
            ];
        }

    return view('purchase.show', compact('item', 'shipping'));
}


    public function checkout(Request $request, Item $item)
    {
        $user = auth()->user();

        if ($item->is_sold || $item->user_id === $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'payment_method' => ['required', 'in:konbini,card'],
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $paymentTypes = $validated['payment_method'] === 'konbini'
            ? ['konbini']
            : ['card'];

        // 注文（未確定）作成
        $order = Order::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'price' => $item->price,
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
            'cancel_url' => route('purchase.show', $item),
        ]);

        $order->update(['stripe_session_id' => $session->id]);

        return redirect($session->url);
    }

    public function success(Request $request, Item $item)
    {
        $user = auth()->user();

        $sessionId = $request->query('session_id');
        if (!$sessionId) {
            abort(400);
        }

        Stripe::setApiKey(config('services.stripe.secret'));
        $session = CheckoutSession::retrieve($sessionId);

        // 自分の注文かチェック
        $order = Order::where('stripe_session_id', $sessionId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // 決済状態チェック（Stripe側）
        if (($session->payment_status ?? null) !== 'paid') {
            return redirect()
                ->route('purchase.show', $item)
                ->with('message', '決済が完了していません');
        }

        DB::transaction(function () use ($item, $order) {
            // 同時購入対策（ロック）
            $lockedItem = Item::where('id', $item->id)->lockForUpdate()->first();

            // すでに売り切れなら何もしない（二重確定防止）
            if ($lockedItem->is_sold) {
                return;
            }

            $lockedItem->update(['is_sold' => true]);
            $order->update(['purchased_at' => now()]);
        });

        // 要件：購入後は商品一覧へ
        return redirect()->route('items.index')->with('message', '購入が完了しました');
    }

    /**
     * マイページに登録されている住所を取り出す
     * - users に住所がある場合
     * - profile（profilesテーブル等）に住所がある場合
     * どちらでも拾えるようにしている
     */
    private function resolveDefaultAddress($user): array
    {
        // ① usersテーブルに住所がある場合（あればここで取れる）
        $postal = $user->postal_code ?? null;
        $address = $user->address ?? null;
        $building = $user->building ?? null;

        // ② profile に住所がある場合（User::profile() がある前提。なければ無視される）
        if ((!$postal || !$address) && method_exists($user, 'profile')) {
            $profile = $user->profile;
            if ($profile) {
                $postal = $postal ?? ($profile->postal_code ?? null);
                $address = $address ?? ($profile->address ?? null);
                $building = $building ?? ($profile->building ?? null);
            }
        }

        return [
            'postal_code' => $postal,
            'address'     => $address,
            'building'    => $building,
        ];
    }
}
