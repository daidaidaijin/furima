<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Http\Requests\ExhibitionRequest;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
 * トップ（おすすめ）
 * ログイン: product_list
 * ゲスト: product_list_guest
 * 検索: ?q=キーワード
 */
public function index(Request $request)
{
    $q = trim($request->query('q', ''));

    $itemsQuery = Item::withCount(['likes', 'comments'])
        ->latest();

    // 検索（商品名title・ブランドbrand・説明description）
    if ($q !== '') {
        $itemsQuery->where(function ($sub) use ($q) {
            $sub->where('title', 'like', "%{$q}%")
                ->orWhere('brand', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%");
        });
    }

    $items = $itemsQuery->get();

    if (auth()->check()) {
        return view('product_list', [
            'items' => $items,
            'activeTab' => 'recommend',
        ]);
    }

    return view('product_list_guest', [
        'items' => $items,
        'activeTab' => 'recommend',
        'q' => $q,
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
    public function store(ExhibitionRequest $request)
    {
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
