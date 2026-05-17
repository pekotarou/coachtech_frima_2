<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

     //保存可能なカラム
    protected $fillable = [
        'product_id',
        'buyer_id',
        'payment',
        'seller_id',
        //購入時点の送付先住所
        'zip_code',
        'residence',
        'building',
    ];

    //購入情報は1つの商品に属する
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    //購入者
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    //出品者
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
