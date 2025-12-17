<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // 出品ページ表示
    public function create()
    {
        $categories = Category::all();
        return view('items.sell', compact('categories'));
    }

    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }

    public function index()
    {
        //新しい順で商品を取得
        $items = Item::latest()->get();
        return view('product_list_guest',compact('items'));
    }

    // 出品処理（保存）
    public function store(Request $request)
    {
        // ① バリデーション
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer',
            'condition' => 'required|string',
            'categories' => 'required|array',
        ]);

        // ② 画像を保存
        $imagePath = $request->file('image')->store('items', 'public');

        // ③ 商品を保存
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

        // ④ カテゴリを紐づけ
        $item->categories()->sync($request->categories);

        // ⑤ 完了
        return redirect('/')->with('success', '商品を出品しました');
    }
}
