<?php

namespace Tests\Feature\Profile;

use App\Models\Order;
use App\Models\Product;
use App\Models\Profile;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    private function createVerifiedUser()
    {
        return User::factory()->create([
            'email_verified_at' => now(),
        ]);
    }

    private function createProduct($user, $name)
    {
        $status = Status::create([
            'status' => '良好',
        ]);

        return Product::create([
            'name' => $name,
            'description' => 'テスト商品の説明です',
            'price' => 1000,
            'image' => 'products/test.jpg',
            'status_id' => $status->id,
            'brand_name' => 'テストブランド', // 修正
            'user_id' => $user->id,
            'order_id' => null,
        ]);
    }

    public function test_プロフィール設定画面が表示される()
    {
        $user = $this->createVerifiedUser();

        $response = $this->actingAs($user)->get('/mypage/profile');

        $response->assertStatus(200);
        $response->assertSee('プロフィール設定');
    }

    public function test_プロフィールを保存できる()
    {
        Storage::fake('public');

        $user = $this->createVerifiedUser();

        $response = $this->actingAs($user)->post('/mypage/profile', [
            'name' => 'テストユーザー',
            'zip_code' => '123-4567',
            'residence' => '東京都渋谷区テスト1-1',
            'building' => 'テストマンション101',
            'image' => UploadedFile::fake()->create('profile.jpg', 100, 'image/jpeg'),
        ]);

        $this->assertDatabaseHas('profiles', [
            'user_id' => $user->id,
            'name' => 'テストユーザー',
            'zip_code' => '123-4567',
            'residence' => '東京都渋谷区テスト1-1',
            'building' => 'テストマンション101',
        ]);

        $response->assertRedirect(route('products.index'));
    }

    public function test_マイページに出品した商品が表示される()
    {
        $user = $this->createVerifiedUser();

        Profile::create([
            'user_id' => $user->id,
            'name' => 'テストユーザー',
            'zip_code' => '123-4567',
            'residence' => '東京都渋谷区テスト1-1',
            'building' => 'テストマンション101',
            'image' => 'profiles/test.jpg',
        ]);

        $this->createProduct($user, '出品した商品');

        $response = $this->actingAs($user)->get('/mypage?page=sell');

        $response->assertStatus(200);
        $response->assertSee('出品した商品');
    }

    public function test_マイページに購入した商品が表示される()
    {
        $buyer = $this->createVerifiedUser();

        Profile::create([
            'user_id' => $buyer->id,
            'name' => '購入者ユーザー',
            'zip_code' => '123-4567',
            'residence' => '東京都渋谷区テスト1-1',
            'building' => 'テストマンション101',
            'image' => 'profiles/test.jpg',
        ]);

        $seller = $this->createVerifiedUser();

        $product = $this->createProduct($seller, '購入した商品');

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

        $response = $this->actingAs($buyer)->get('/mypage?page=buy');

        $response->assertStatus(200);
        $response->assertSee('購入した商品');
    }
}