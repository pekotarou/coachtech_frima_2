<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    //保存可能なカラム
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'status_id',
        'brand_id',
        'user_id',
        'order_id',
    ];


    //商品は複数のカテゴリーを持つ
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category');
    }

    //商品は1つの状態に属する
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    //商品は1つのブランドに属する
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    //商品は1人のユーザーに属する
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //商品は1つの購入情報を持つ
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
}
