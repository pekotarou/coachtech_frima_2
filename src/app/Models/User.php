<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Profile;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    //Userは1つのProfileを持つ設定
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }


    //1人のユーザーは複数の商品を出品できる
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // 追加: ユーザーが購入した注文
    public function boughtOrders()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    // 追加: ユーザーが販売した注文
    public function soldOrders()
    {
        return $this->hasMany(Order::class, 'seller_id');
    }
}
