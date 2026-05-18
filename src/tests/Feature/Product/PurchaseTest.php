<?php

namespace Tests\Feature\Product;

use App\Models\Order;
use App\Models\Product;
use App\Models\Profile;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    private function createVerifiedUser()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        Profile::create([
            'user_id' => $user->id,
            'name' => 'テストユーザー',
            'zip_code' => '123-4567',
            'residence' => '東京都渋谷区テスト1-1',
            'building' => 'テストマンション101',
            'image' => 'profiles/test.jpg',
        ]);

        return $user;
    }

    private function createProduct($seller = null)
    {
        $seller = $seller ?? User::factory()->create([
            'email_verified_at' => now(),
        ]);

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

    public function test_購入画面が表示される()
    {
        $buyer = $this->createVerifiedUser();
        $product = $this->createProduct();

        $response = $this->actingAs($buyer)->get('/purchase/' . $product->id);

        $response->assertStatus(200);
        $response->assertSee('テスト商品');
        $response->assertSee('支払い方法');
        $response->assertSee('配送先');
    }

    public function test_支払い方法が未選択の場合はバリデーションメッセージが表示される()
    {
        $buyer = $this->createVerifiedUser();
        $product = $this->createProduct();

        $response = $this->actingAs($buyer)->post('/purchase/' . $product->id, [
            'payment' => '',
        ]);

        $response->assertSessionHasErrors('payment');
    }

    public function test_自分が出品した商品は購入できない()
    {
        $seller = $this->createVerifiedUser();

        $product = $this->createProduct($seller);

        $response = $this->actingAs($seller)->post('/purchase/' . $product->id, [
            'payment' => 'カード払い',
        ]);

        $response->assertRedirect(route('products.show', $product->id));
        $response->assertSessionHas('error', '自分が出品した商品は購入できません。');
    }

    public function test_購入済み商品は購入できない()
    {
        $buyer = $this->createVerifiedUser();
        $seller = $this->createVerifiedUser();
        $product = $this->createProduct($seller);

        $order = Order::create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'payment' => 'カード払い',
            'zip_code' => '123-4567',
            'residence' => '東京都渋谷区テスト1-1',
            'building' => 'テストマンション101',
        ]);

        $product->update([
            'order_id' => $order->id,
        ]);

        $anotherBuyer = $this->createVerifiedUser();

        $response = $this->actingAs($anotherBuyer)->post('/purchase/' . $product->id, [
            'payment' => 'カード払い',
        ]);

        $response->assertRedirect(route('products.show', $product->id));
        $response->assertSessionHas('error', 'この商品はすでに購入されています。');
    }
}