<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class Profile extends Model
{
    use HasFactory;
    //プロフィールテーブルの保存に関して
    protected $fillable = [
        'user_id',
        'name',
        'zip_code',
        'residence',
        'building',
        'image',
    ];

    //ProfileはUserに属する設定
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
