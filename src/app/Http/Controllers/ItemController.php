<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * トップ（おすすめ）
     * ログイン: product_list
     * ゲスト: product_list_guest
     */
    public function index()
    {
        // おすすめ（今は新着順）
        $items = Item::withCount(['likes', 'comments'])
            ->latest()
            ->get();

        if (auth()->check()) {
            return view('product_list', [
                'items' => $items,
                'activeTab' => 'recommend',
            ]);
        }

        return view('product_list_guest', [
            'items' => $items,
            'activeTab' => 'recommend',
        ]);
    }

    /**
     * マイリスト（いいねした商品）
     * ログイン必須（ルート側で auth を付ける想定）
     */
    public function mylist()
    {
        $items = Item::withCount(['likes', 'comments'])
            ->whereHas('likes', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->latest()
            ->get();

        return view('product_list', [
            'items' => $items,
            'activeTab' => 'mylist',
        ]);
    }

    /**
     * 商品詳細
     */
    public function show(Item $item)
    {
        $item->load([
            'categories',
            'comments.user',
        ])->loadCount([
            'likes',
            'comments',
        ]);

        $isLiked = false;
        if (auth()->check()) {
            $isLiked = $item->likes()
                ->where('user_id', auth()->id())
                ->exists();
        }

        return view('items.show', compact('item', 'isLiked'));
    }

    /**
     * 出品ページ表示
     */
    public function create()
    {
        $categories = Category::all();
        return view('items.sell', compact('categories'));
    }

    /**
     * 出品処理（保存）
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer',
            'condition' => 'required|string',
            'categories' => 'required|array',
        ]);

        // 画像保存
        $imagePath = $request->file('image')->store('items', 'public');

        // 商品保存
        $item = Item::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'brand' => $request->brand,
            'condition' => $request->condition,
            'description' => $request->description,
            'price' => $request->price,
            'image_path' => $imagePath,
            'is_sold' => false,
        ]);

        // カテゴリ紐づけ
        $item->categories()->sync($request->categories);

        return redirect('/')->with('success', '商品を出品しました');
    }
}
