<?php

namespace Tests\Feature\Product;

use App\Models\Brand;
use App\Models\Heart;
use App\Models\Product;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MylistTest extends TestCase
{
    use RefreshDatabase;

    private function createProduct($name)
    {
        $seller = User::factory()->create();

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
            'user_id' => $seller->id,
            'order_id' => null,
        ]);
    }

    public function test_いいねした商品がマイリストに表示される()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $product = $this->createProduct('いいねした商品');

        Heart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('いいねした商品');
    }

    public function test_いいねしていない商品はマイリストに表示されない()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $likedProduct = $this->createProduct('いいねした商品');
        $notLikedProduct = $this->createProduct('いいねしていない商品');

        Heart::create([
            'user_id' => $user->id,
            'product_id' => $likedProduct->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('いいねした商品');
        $response->assertDontSee('いいねしていない商品');
    }

    public function test_未ログインユーザーのマイリストは空表示になる()
    {
        $this->createProduct('表示されない商品');

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        //未ログイン時のマイリストには商品を表示しない
        $response->assertDontSee('表示されない商品');
    }
}