<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')
            ->constrained()//外部キー制約(owner_idにアクセスできる)
            ->onUpdate('cascade')//Owner->Shopと外部キー制約を設定しているため追加設定が必要。
            ->onDelete('cascade');//Ownerが消えたらShopも消えるようにする
            $table->string('filename');
            $table->string('title')->nullable();
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
        Schema::dropIfExists('images');
    }
}
