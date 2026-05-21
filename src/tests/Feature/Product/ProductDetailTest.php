<?php

namespace Tests\Feature\Product;

use App\Models\Category;
use App\Models\Product;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductDetailTest extends TestCase
{
    use RefreshDatabase;

    private function createProduct()
    {
        $user = User::factory()->create();

        $status = Status::create([
            'status' => '良好',
        ]);

        $category = Category::create([
            'content' => 'ファッション',
        ]);

        $product = Product::create([
            'name' => 'テスト商品',
            'description' => 'テスト商品の説明です',
            'price' => 1000,
            'image' => 'products/test.jpg',
            'status_id' => $status->id,
            'brand_name' => 'テストブランド', 
            'user_id' => $user->id,
            'order_id' => null,
        ]);

        //商品にカテゴリーを紐づける
        $product->categories()->attach($category->id);

        return $product;
    }

    public function test_商品詳細画面が表示される()
    {
        $product = $this->createProduct();

        $response = $this->get('/item/' . $product->id);

        $response->assertStatus(200);
    }

    public function test_商品詳細画面に商品情報が表示される()
    {
        $product = $this->createProduct();

        $response = $this->get('/item/' . $product->id);

        $response->assertStatus(200);
        $response->assertSee('テスト商品');
        $response->assertSee('テスト商品の説明です');
        $response->assertSee('1,000');
        $response->assertSee('テストブランド');
        $response->assertSee('良好');
        $response->assertSee('ファッション');
    }

    public function test_存在しない商品IDの場合は404になる()
    {
        $response = $this->get('/item/9999');

        $response->assertStatus(404);
    }
}