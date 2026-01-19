<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    use HasFactory;

    // updateOrCreateで user_id も保存できるようにする
    protected $fillable = [
        'user_id',
        'postal_code',
        'address',
        'building',
        // もしテーブルに order_id を残して使うなら、必要に応じて追加:
        // 'order_id',
    ];

    /**
     * 配送先住所はユーザーに属する
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
