<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'price',
        'payment_method',
        'stripe_session_id',
        'purchased_at',
        'shipping_postal_code',
        'shipping_address',
        'shipping_building',
    ];

    protected $casts = [
        'purchased_at' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function item() {
        return $this->belongsTo(Item::class);
    }
}
