<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            //コメントしたユーザー
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            //コメントされた商品
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            //コメント内容
            $table->text('comment');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
}