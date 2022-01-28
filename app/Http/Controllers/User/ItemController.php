<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function index()
    {
        // Stockの合計をグループ化->数量が1以上 
        $stocks = DB::table('t_stocks')
        ->select('product_id',//select内でsumを使うためクエリビルダのDB::rawで対応
        DB::raw('sum(quantity) as quantity'))//DB::rawでSQL文を書くことができる
        ->groupBy('product_id')
        ->having('quantity', '>', 1);
        // Having・・groupByの後に条件指定。
        // Where・・groupByより前に条件指定

        // 前ページの $stocksをサブクエリとして設定
        // products、shops、stocksをjoin句で紐付けて
        // where句で is_sellingがtrue かの条件指定
        $products = DB::table('products')
        ->joinSub($stocks, 'stock', function($join){
        $join->on('products.id', '=', 'stock.product_id');
        })
        ->join('shops', 'products.shop_id', '=', 'shops.id')
        ->join('secondary_categories', 'products.secondary_category_id', '=','secondary_categories.id')
        ->join('images as image1', 'products.image1', '=', 'image1.id')
        ->where('shops.is_selling', true)
        ->where('products.is_selling', true)
        ->select('products.id as id', 'products.name as name', 
        'products.price','products.sort_order as sort_order',
        'products.information', 'secondary_categories.name as category',
        'image1.filename as filename')//色々なところにnameがあるのでnameを統一するためのas
        ->get();

        return view('user.index',compact('products')); 
    }
}
