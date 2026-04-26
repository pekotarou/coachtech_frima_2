<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    //statusを保存可能にする
    protected $fillable = [
        'status',
    ];

    //1つの商品状態は複数の商品を持つ
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
