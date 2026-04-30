<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'comment',
    ];

    //コメントは1人のユーザーに属する
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //コメントは1つの商品に属する
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}