<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop;
use App\Models\SecondaryCategory;
use App\Models\Image;
use App\Models\Stock;

class Product extends Model
{
    use HasFactory;

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
    // メソッド名をモデル名から変える場合は第２引数必要
    // (カラム名と同じメソッドは指定できないので名称変更)
    public function category(){
        return $this->belongsTo(SecondaryCategory::class, 'secondary_category_id');
    }

    // 第２引数で_id がつかない場合は 第３引数で指定必要
    public function imageFirst(){
        return $this->belongsTo(Image::class, 'image1', 'id');
    }

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }
}
