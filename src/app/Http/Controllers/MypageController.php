<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Order;

class MypageController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $tab = $request->query('tab', 'sell'); // sell / buy

        // 出品した商品（自分が出品）
        $sellingItems = Item::where('user_id', $user->id)
            ->latest()
            ->get();

        // 購入した商品（自分が買った注文）
        $purchasedItemIds = Order::where('user_id', $user->id)
            ->whereNotNull('purchased_at')
            ->latest('purchased_at')
            ->pluck('item_id');

        $purchasedItems = Item::whereIn('id', $purchasedItemIds)
            ->get()
            // purchased_at の順に並べたいので、pluck順に並び替え
            ->sortBy(function ($item) use ($purchasedItemIds) {
                return $purchasedItemIds->search($item->id);
            })
            ->values();

        return view('mypage.index', compact('tab', 'sellingItems', 'purchasedItems'));
    }
}
