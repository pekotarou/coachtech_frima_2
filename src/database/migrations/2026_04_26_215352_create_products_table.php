<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            //商品名
            $table->string('name');

            //商品説明
            $table->string('description');


            //商品価格
            $table->integer('price');

            //商品画像パス
            $table->string('image');

            //カテゴリーID
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();

            //商品状態ID
            $table->foreignId('status_id')->constrained()->cascadeOnDelete();

            //ブランドID
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();

            //出品者ID
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            //購入情報ID
            //ordersテーブルはまだ作っていないので、今は外部キーを付けない
            $table->unsignedBigInteger('order_id')->nullable();




            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
