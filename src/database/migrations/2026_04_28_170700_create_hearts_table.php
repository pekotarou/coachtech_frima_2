<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hearts', function (Blueprint $table) {
            $table->id();
            //いいねしたユーザー
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            //いいねされた商品
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            //同じユーザーが同じ商品に複数いいねできないようにする
            $table->unique(['user_id', 'product_id']);

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
        Schema::dropIfExists('hearts');
    }
}
