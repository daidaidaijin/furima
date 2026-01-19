<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;

class ShippingAddressController extends Controller
{
    /**
     * 住所変更画面
     */
    public function edit(Item $item)
    {
        $user = auth()->user();

        // 購入不可条件
        if ($item->is_sold || $item->user_id === $user->id) {
            abort(403);
        }

        // ① 購入用住所があればそれを表示
        $shipping = ShippingAddress::where('user_id', $user->id)->first();

        // ② 無ければマイページ住所を初期値として使う
        if (!$shipping) {
            $address = trim(implode('', array_filter([
                $user->address_pref,
                $user->address_city,
                $user->address_detail,
            ])));

            $shipping = (object) [
                'postal_code' => $user->postal_code ?? '',
                'address'     => $address ?? '',
                'building'    => '',
            ];
        }

        return view('purchase.address_edit', compact('item', 'shipping'));
    }

    /**
     * 住所更新（購入時のみ上書き）
     */
    public function update(Request $request, Item $item)
    {
        $user = auth()->user();

        // 購入不可条件
        if ($item->is_sold || $item->user_id === $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'postal_code' => ['required', 'string', 'max:20'],
            'address'     => ['required', 'string', 'max:255'],
            'building'    => ['nullable', 'string', 'max:255'],
        ]);

        // 🔴 ここが重要：Model直叩きで確実に保存する
        ShippingAddress::updateOrCreate(
            ['user_id' => $user->id],
            [
                'postal_code' => $validated['postal_code'],
                'address'     => $validated['address'],
                'building'    => $validated['building'] ?? null,
            ]
        );

        return redirect()
            ->route('purchase.show', $item)
            ->with('message', '配送先を更新しました');
    }
}
