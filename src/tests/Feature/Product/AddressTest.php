<?php

namespace Tests\Feature\Product;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Profile;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressTest extends TestCase
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

    private function createProduct()
    {
        $seller = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $brand = Brand::create([
            'name' => 'テストブランド',
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
            'brand_id' => $brand->id,
            'user_id' => $seller->id,
            'order_id' => null,
        ]);
    }

    public function test_住所変更画面が表示される()
    {
        $user = $this->createVerifiedUser();
        $product = $this->createProduct();

        $response = $this->actingAs($user)->get('/purchase/address/' . $product->id);

        $response->assertStatus(200);
        $response->assertSee('住所の変更');
        $response->assertSee('郵便番号');
        $response->assertSee('住所');
        $response->assertSee('建物名');
    }

    public function test_郵便番号が未入力の場合はバリデーションメッセージが表示される()
    {
        $user = $this->createVerifiedUser();
        $product = $this->createProduct();

        $response = $this->actingAs($user)->post('/purchase/address/' . $product->id, [
            'zip_code' => '',
            'residence' => '北海道札幌市テスト1-1',
            'building' => 'テストビル202',
        ]);

        $response->assertSessionHasErrors('zip_code');
    }

    public function test_住所を更新すると購入画面に戻りsessionに保存される()
    {
        $user = $this->createVerifiedUser();
        $product = $this->createProduct();

        $response = $this->actingAs($user)->post('/purchase/address/' . $product->id, [
            'zip_code' => '987-6543',
            'residence' => '北海道札幌市テスト2-2',
            'building' => 'サンプルマンション303',
        ]);

        // 修正: 更新後は購入画面へ戻る
        $response->assertRedirect(route('products.purchase', $product->id));

        // 修正: sessionに送付先住所が保存されているか確認
        $this->assertEquals(
            [
                'zip_code' => '987-6543',
                'residence' => '北海道札幌市テスト2-2',
                'building' => 'サンプルマンション303',
            ],
            session('purchase_address.' . $product->id)
        );
    }
}