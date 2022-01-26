<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Image;
use App\Models\SecondaryCategory;
use App\Models\Owner;
use App\Models\Shop;
use App\Models\PrimaryCategory;

class ProductController extends Controller
{
    //ログイン済みのユーザーのみ表示させるためコンストラクタで下記を設定
    public function __construct()
    {
        $this->middleware('auth:owners');
        $this->middleware(function($request, $next){
            $id = $request->route()->parameter('product');
            if(!is_null($id)){
                $productsOwnerId = Product::findOrFail($id)->shop->owner->id;
                $productId = (int)$productsOwnerId;
                $ownerId = Auth::id();
            if($productId !== $ownerId){
                abort(404);
            }
        }
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // //ログインしているOwnerのshopのproductを取得
        // $products = Owner::findOrFail(Auth::id())->shop->product;
        // 上記のN + 1問題の対策 -> SQL文を一つにまとめる(リレーション先のリレーション情報を取得)
        // withメソッド、リレーションをドットでつなぐ
        $ownerInfo = Owner::with('shop.product.imageFirst')
        ->where('id', Auth::id())->get();

        // foreach($ownerInfo as $owner)
        // dd($owner->shop->product);//出力結果 Productが一つずつ
        // foreach($owner->shop->product as $product)
        // {
        //     dd($product->imageFirst->filename);//出力結果 sample01.jpg
        // }

        return view('owner.products.index', compact('ownerInfo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shops = Shop::where('owner_id', Auth::id())
        ->select('id', 'name')->get();

        $images = Image::where('owner_id', Auth::id())
        ->select('id', 'title','filename')
        ->orderBy('updated_at', 'desc')->get();

        // n+1問題
        $categories = PrimaryCategory::with('secondary')->get();
        return view('owner.products.create', 
        compact('shops','images','categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
