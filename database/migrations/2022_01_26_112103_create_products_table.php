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
            $table->string('name');
            $table->text('information');
            $table->unsignedInteger('price');
            $table->boolean('is_selling');
            $table->integer('sort_order')->nullable();
            //親を削除するか, 親を削除したときに合わせて削除するか
            //shopが消えた時はproductも消えるので cascadeあり
            $table->foreignId('shop_id')
            ->constrained()
            ->onUpdate('cascade')
            ->onDelete('cascade');
            //親を削除するか, 親を削除したときに合わせて削除するか
            //カテゴリーは消さないので cascadeはなし
            $table->foreignId('secondary_category_id')
            ->constrained();
            $table->foreignId('image1')
            ->nullable()// null許可、
            ->constrained('images');//image1はカラム名ではないのでテーブル名を指定
            $table->foreignId('image2')
            ->nullable()// null許可、
            ->constrained('images');//image1はカラム名ではないのでテーブル名を指定
            $table->foreignId('image3')
            ->nullable()// null許可、
            ->constrained('images');//image1はカラム名ではないのでテーブル名を指定
            $table->foreignId('image4')
            ->nullable()// null許可、
            ->constrained('images');//image1はカラム名ではないのでテーブル名を指定
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
