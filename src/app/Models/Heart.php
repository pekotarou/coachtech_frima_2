<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Heart extends Model
{
    use HasFactory;

    //保存可能なカラム
    protected $fillable = [
        'user_id',
        'product_id',
    ];

    //いいねは1人のユーザーに属する
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //いいねは1つの商品に属する
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
