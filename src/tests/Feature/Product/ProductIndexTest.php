<?php

namespace Tests\Feature\Product;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductIndexTest extends TestCase
{
    use RefreshDatabase;

    private function createProduct($name)
    {
        $user = User::factory()->create();

        $brand = Brand::create([
            'name' => 'テストブランド',
        ]);

        $status = Status::create([
            'status' => '良好',
        ]);

        return Product::create([
            'name' => $name,
            'description' => 'テスト商品の説明です',
            'price' => 1000,
            'image' => 'products/test.jpg',
            'status_id' => $status->id,
            'brand_id' => $brand->id,
            'user_id' => $user->id,
            'order_id' => null,
        ]);
    }

    public function test_商品一覧画面が表示される()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_商品一覧画面に商品が表示される()
    {
        $product = $this->createProduct('テスト商品');

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('テスト商品');
    }

    public function test_商品名で検索できる()
    {
        $this->createProduct('腕時計');
        $this->createProduct('ノートPC');

        $response = $this->get('/?keyword=腕時計');

        $response->assertStatus(200);
        $response->assertSee('腕時計');
        $response->assertDontSee('ノートPC');
    }
}