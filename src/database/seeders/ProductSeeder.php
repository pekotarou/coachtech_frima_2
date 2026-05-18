<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Status;
use App\Models\User;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // 商品の出品者として1人目のユーザーを使う
        $user = User::first();

        if (!$user) {
            return;
        }

        // 仮で最初のカテゴリーを全商品に紐づける
        $category = Category::first();

        if (!$category) {
            return;
        }

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

        foreach ($products as $productData) {
            $status = Status::where('status', $productData['status'])->first();

            if (!$status) {
                continue;
            }

            // productsテーブルにはcategory_idを保存しない
            $product = Product::create([
                'name' => $productData['name'],
                'price' => $productData['price'],
                'description' => $productData['description'],
                'image' => $productData['image'],

                //productsテーブルにブランド名を保存
                'brand_name' => $productData['brand'],

                'status_id' => $status->id,
                'user_id' => $user->id,
                'order_id' => null,
            ]);

            // 中間テーブル product_category にカテゴリーを保存
            $product->categories()->attach($category->id);
        }
    }
}