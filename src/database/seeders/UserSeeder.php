<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        //商品データの出品者として使うテストユーザー
        User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',

            //パスワードは必ずハッシュ化する
            //ログイン時のパスワードは password
            'password' => Hash::make('password'),
        ]);
    }
}