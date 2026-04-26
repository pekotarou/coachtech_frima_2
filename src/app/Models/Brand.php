<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    //nameを保存可能にする
    protected $fillable = [
        'name',
    ];

    //1つのブランドは複数の商品を持つ
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
