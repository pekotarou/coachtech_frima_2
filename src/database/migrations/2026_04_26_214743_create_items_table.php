<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            //出品者ID
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();

            //商品名
            $table->string('name');

            //価格
            $table->integer('price');

            //商品説明
            $table->text('description')->nullable();

            //商品画像
            $table->string('image')->nullable();

            // 修正: 売り切れ表示用
            $table->boolean('is_sold')->default(false);

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
        Schema::dropIfExists('items');
    }
}
