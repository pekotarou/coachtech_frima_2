<?php

namespace Tests\Feature\Product;

use App\Models\Heart;
use App\Models\Product;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HeartTest extends TestCase
{
    use RefreshDatabase;

    private function createProduct()
    {
        $seller = User::factory()->create();

        $status = Status::create([
            'status' => '良好',
        ]);

        return Product::create([
            'name' => 'テスト商品',
            'description' => 'テスト商品の説明です',
            'price' => 1000,
            'image' => 'products/test.jpg',
            'status_id' => $status->id,
            'brand_name' => 'テストブランド', 
            'user_id' => $seller->id,
            'order_id' => null,
        ]);
    }

    public function test_ログインユーザーは商品にいいねできる()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $product = $this->createProduct();

        $response = $this->actingAs($user)->post('/item/' . $product->id . '/heart');

        $this->assertDatabaseHas('hearts', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $response->assertRedirect(route('products.show', $product->id));
    }

    public function test_いいね済みの商品をもう一度押すといいね解除できる()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $product = $this->createProduct();

        Heart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $response = $this->actingAs($user)->post('/item/' . $product->id . '/heart');

        $this->assertDatabaseMissing('hearts', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $response->assertRedirect(route('products.show', $product->id));
    }

    public function test_未ログインユーザーはいいねできずログイン画面へ遷移する()
    {
        $product = $this->createProduct();

        $response = $this->post('/item/' . $product->id . '/heart');

        $response->assertRedirect(route('login'));

        $this->assertDatabaseMissing('hearts', [
            'product_id' => $product->id,
        ]);
    }
}