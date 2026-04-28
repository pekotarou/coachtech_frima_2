<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

             // 修正: 購入された商品ID
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            // 修正: 購入者ID
            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();

            // 修正: 支払い方法
            $table->string('payment');

            // 修正: 出品者ID
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();

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
        Schema::dropIfExists('orders');
    }
}
