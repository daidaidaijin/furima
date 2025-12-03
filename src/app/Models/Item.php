<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'brand', 'description', 'price'
    ];

    public function categories() {
        return $this->belongsToMany(Category::class, 'category_items');
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }
}
