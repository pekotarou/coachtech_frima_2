<?php

namespace Tests\Feature\Product;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductStoreTest extends TestCase
{
    use RefreshDatabase;

    private function createVerifiedUser()
    {
        return User::factory()->create([
            'email_verified_at' => now(),
        ]);
    }

    private function createMasterData()
    {
        $category = Category::create([
            'content' => 'ファッション',
        ]);

        $status = Status::create([
            'status' => '良好',
        ]);

        $brand = Brand::create([
            'name' => 'テストブランド',
        ]);

        return [$category, $status, $brand];
    }

    public function test_商品出品画面が表示される()
    {
        $user = $this->createVerifiedUser();

        $response = $this->actingAs($user)->get('/sell');

        $response->assertStatus(200);
    }

    public function test_商品名が未入力の場合はバリデーションメッセージが表示される()
    {
        $user = $this->createVerifiedUser();
        [$category, $status, $brand] = $this->createMasterData();

        $response = $this->actingAs($user)->post('/sell', [
            'name' => '',
            'description' => 'テスト商品の説明です',
            'price' => 1000,
            'image' => UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg'),
            'category_ids' => [$category->id],
            'status_id' => $status->id,
            'brand_name' => 'テストブランド',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_商品説明が未入力の場合はバリデーションメッセージが表示される()
    {
        $user = $this->createVerifiedUser();
        [$category, $status, $brand] = $this->createMasterData();

        $response = $this->actingAs($user)->post('/sell', [
            'name' => 'テスト商品',
            'description' => '',
            'price' => 1000,
            'image' => UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg'),
            'category_ids' => [$category->id],
            'status_id' => $status->id,
            'brand_name' => 'テストブランド',
        ]);

        $response->assertSessionHasErrors('description');
    }

    public function test_商品価格が未入力の場合はバリデーションメッセージが表示される()
    {
        $user = $this->createVerifiedUser();
        [$category, $status, $brand] = $this->createMasterData();

        $response = $this->actingAs($user)->post('/sell', [
            'name' => 'テスト商品',
            'description' => 'テスト商品の説明です',
            'price' => '',
            'image' => UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg'),
            'category_ids' => [$category->id],
            'status_id' => $status->id,
            'brand_name' => 'テストブランド',
        ]);

        $response->assertSessionHasErrors('price');
    }

    public function test_商品画像が未選択の場合はバリデーションメッセージが表示される()
    {
        $user = $this->createVerifiedUser();
        [$category, $status, $brand] = $this->createMasterData();

        $response = $this->actingAs($user)->post('/sell', [
            'name' => 'テスト商品',
            'description' => 'テスト商品の説明です',
            'price' => 1000,
            'category_ids' => [$category->id],
            'status_id' => $status->id,
            'brand_name' => 'テストブランド',
        ]);

        $response->assertSessionHasErrors('image');
    }

    public function test_カテゴリーが未選択の場合はバリデーションメッセージが表示される()
    {
        $user = $this->createVerifiedUser();
        [$category, $status, $brand] = $this->createMasterData();

        $response = $this->actingAs($user)->post('/sell', [
            'name' => 'テスト商品',
            'description' => 'テスト商品の説明です',
            'price' => 1000,
            'image' => UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg'),
            'category_ids' => [],
            'status_id' => $status->id,
            'brand_name' => 'テストブランド',
        ]);

        $response->assertSessionHasErrors('category_ids');
    }

    public function test_商品状態が未選択の場合はバリデーションメッセージが表示される()
    {
        $user = $this->createVerifiedUser();
        [$category, $status, $brand] = $this->createMasterData();

        $response = $this->actingAs($user)->post('/sell', [
            'name' => 'テスト商品',
            'description' => 'テスト商品の説明です',
            'price' => 1000,
            'image' => UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg'),
            'category_ids' => [$category->id],
            'status_id' => '',
            'brand_name' => 'テストブランド',
        ]);

        $response->assertSessionHasErrors('status_id');
    }

    public function test_正しい情報を入力すると商品が保存される()
    {
        Storage::fake('public');

        $user = $this->createVerifiedUser();
        [$category, $status, $brand] = $this->createMasterData();

        $response = $this->actingAs($user)->post('/sell', [
            'name' => 'テスト商品',
            'description' => 'テスト商品の説明です',
            'price' => 1000,
            'image' => UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg'),
            'category_ids' => [$category->id],
            'status_id' => $status->id,
            'brand_name' => 'テストブランド',
        ]);
      

        $this->assertDatabaseHas('products', [
            'name' => 'テスト商品',
            'description' => 'テスト商品の説明です',
            'price' => 1000,
            'status_id' => $status->id,
            'user_id' => $user->id,
        ]);
        $this->assertDatabaseHas('brands', [
            'name' => 'テストブランド',
        ]);

        $product = Product::where('name', 'テスト商品')->first();

        $this->assertDatabaseHas('product_category', [
            'product_id' => $product->id,
            'category_id' => $category->id,
        ]);

        $response->assertRedirect(route('products.index'));
    }
}