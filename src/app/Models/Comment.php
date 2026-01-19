<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // 保存を許可するカラム
    protected $fillable = [
        'user_id',
        'item_id',
        'comment',
    ];

    // コメントを書いたユーザー
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    // コメントされた商品
    public function item()
    {
        return $this->belongsTo(\App\Models\Item::class);
    }
}
