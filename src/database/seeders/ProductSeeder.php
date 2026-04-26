<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Status;
use App\Models\User;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // 修正: 商品の出品者として1人目のユーザーを使う
        $user = User::first();

        // ユーザーがいない場合は、商品を作れないので処理を止める
        if (!$user) {
            return;
        }

        $category = Category::first();

        $products = [
            [
                'name' => '腕時計',
                'price' => 15000,
                'brand' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image' => 'products/watch.jpg',
                'status' => '良好',
            ],
            [
                'name' => 'HDD',
                'price' => 5000,
                'brand' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'image' => 'products/hdd.jpg',
                'status' => '目立った傷や汚れなし',
            ],
            [
                'name' => '玉ねぎ3束',
                'price' => 300,
                'brand' => 'なし',
                'description' => '新鮮な玉ねぎ3束のセット',
                'image' => 'products/onion.jpg',
                'status' => 'やや傷や汚れあり',
            ],
            [
                'name' => '革靴',
                'price' => 4000,
                'brand' => 'なし',
                'description' => 'クラシックなデザインの革靴',
                'image' => 'products/leather-shoes.jpg',
                'status' => '状態が悪い',
            ],
            [
                'name' => 'ノートPC',
                'price' => 45000,
                'brand' => 'なし',
                'description' => '高性能なノートパソコン',
                'image' => 'products/laptop.jpg',
                'status' => '良好',
            ],
            [
                'name' => 'マイク',
                'price' => 8000,
                'brand' => 'なし',
                'description' => '高音質のレコーディング用マイク',
                'image' => 'products/microphone.jpg',
                'status' => '目立った傷や汚れなし',
            ],
            [
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'brand' => 'なし',
                'description' => 'おしゃれなショルダーバッグ',
                'image' => 'products/shoulder-bag.jpg',
                'status' => 'やや傷や汚れあり',
            ],
            [
                'name' => 'タンブラー',
                'price' => 500,
                'brand' => 'なし',
                'description' => '使いやすいタンブラー',
                'image' => 'products/tumbler.jpg',
                'status' => '状態が悪い',
            ],
            [
                'name' => 'コーヒーミル',
                'price' => 4000,
                'brand' => 'Starbucks',
                'description' => '手動のコーヒーミル',
                'image' => 'products/coffee-mill.jpg',
                'status' => '良好',
            ],
            [
                'name' => 'メイクセット',
                'price' => 2500,
                'brand' => 'なし',
                'description' => '便利なメイクアップセット',
                'image' => 'products/makeup-set.jpg',
                'status' => '目立った傷や汚れなし',
            ],
        ];

        foreach ($products as $product) {
            $brand = Brand::where('name', $product['brand'])->first();
            $status = Status::where('status', $product['status'])->first();

            Product::create([
                'name' => $product['name'],
                'price' => $product['price'],
                'description' => $product['description'],
                'image' => $product['image'],

                // 修正: 今回は仮で最初のカテゴリーを入れる
                'category_id' => $category->id,

                'brand_id' => $brand->id,
                'status_id' => $status->id,
                'user_id' => $user->id,

                // 未購入なのでnull
                'order_id' => null,
            ]);
        }
    }
}