<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Item;
use Illuminate\Http\Request;

class ShippingAddressController extends Controller
{
    public function edit(Item $item)
    {
        $user = auth()->user();

        if ($item->is_sold || $item->user_id === $user->id) {
            abort(403);
        }

        // ① 購入用住所があればそれ
        $shipping = $user->shippingAddress;

        // ② 無ければマイページ住所を初期値として使う
        if (!$shipping) {
            $address = trim(implode('', array_filter([
                $user->address_pref,
                $user->address_city,
                $user->address_detail,
            ])));

            $shipping = (object) [
                // 設計書に合わせるなら 123-4567 形式で保存/表示したい
                // もし users.postal_code が "11111111" のようにハイフン無しなら、表示時だけ整形してもOK
                'postal_code' => $user->postal_code ?? '',
                'address'     => $address ?? '',
                'building'    => '',
            ];
        }

        return view('purchase.address_edit', compact('item', 'shipping'));
    }

    public function update(AddressRequest $request, Item $item)
    {
        $user = auth()->user();

        if ($item->is_sold || $item->user_id === $user->id) {
            abort(403);
        }

        $validated = $request->validated();

        // ✅ 購入時だけ上書き：shipping_addressesに保存（usersは触らない）
        $user->shippingAddress()->updateOrCreate(
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
