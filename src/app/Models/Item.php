<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'brand',
        'condition',
        'description',
        'price',
        'image_path',
        'is_sold',
    ];

    public function categories() {
        return $this->belongsToMany(Category::class, 'category_items');
    }

    public function orders() {
        return $this->hasOne(\App\Models\Order::class);
    }

    public function likes(){
        return $this->hasMany(Like::class);
    }

    public function comments()
{
    return $this->hasMany(\App\Models\Comment::class);
}
}
