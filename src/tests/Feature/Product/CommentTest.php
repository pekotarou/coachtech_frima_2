<?php

namespace Tests\Feature\Product;

use App\Models\Comment;
use App\Models\Product;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
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
            'brand_name' => 'テストブランド', // 修正
            'user_id' => $seller->id,
            'order_id' => null,
        ]);
    }

    public function test_コメントが未入力の場合はバリデーションメッセージが表示される()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $product = $this->createProduct();

        $response = $this->actingAs($user)->post('/item/' . $product->id . '/comment', [
            'comment' => '',
        ]);

        $response->assertSessionHasErrors([
            'comment' => 'コメントを入力してください。',
        ]);
    }

    public function test_コメントが256文字以上の場合はバリデーションメッセージが表示される()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $product = $this->createProduct();

        $response = $this->actingAs($user)->post('/item/' . $product->id . '/comment', [
            'comment' => str_repeat('あ', 256),
        ]);

        $response->assertSessionHasErrors([
            'comment' => 'コメントは255文字以内で入力してください。',
        ]);
    }

    public function test_コメントを入力するとcommentsテーブルに保存される()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $product = $this->createProduct();

        $response = $this->actingAs($user)->post('/item/' . $product->id . '/comment', [
            'comment' => 'テストコメントです',
        ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'comment' => 'テストコメントです',
        ]);

        $response->assertRedirect(route('products.show', $product->id));
    }
}