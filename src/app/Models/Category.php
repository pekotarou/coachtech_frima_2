<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    //contentを保存可能にする
    protected $fillable = [
        'content',
    ];

    //1つのカテゴリーは複数の商品を持つ
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
