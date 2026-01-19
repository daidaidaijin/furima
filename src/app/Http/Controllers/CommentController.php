<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Item $item)
    {
        $validated = $request->validate([
            'comment' => ['required', 'string', 'max:255'],
        ]);

        Comment::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,    // item_id を使う（items_idじゃない）
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'コメントを送信しました');
    }
}
