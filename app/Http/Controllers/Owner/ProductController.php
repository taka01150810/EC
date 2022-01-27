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
use Throwable;
use Illuminate\Support\Facades\DB;//クエリビルダ
use Illuminate\Support\Facades\Log;
use App\Models\Stock;
use App\Http\Requests\ProductRequest;

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
    public function store(ProductRequest $request)
    {
        try{
            DB::transaction(function()use($request){//無名関数内で親の変数を使うにはuseが必要
                $product = Product::create([
                    'name' => $request->name,
                    'information' => $request->information,
                    'price' => $request->price,
                    'sort_order' => $request->sort_order,
                    'shop_id' => $request->shop_id,
                    'secondary_category_id' => $request->category,
                    'image1' => $request->image1,
                    'is_selling' => $request->is_selling,
                ]);

                //外部キー向けのidを取得。
                //Stock::createで作成する場合はモデル側にfillableも必要
                Stock::create([
                    'product_id' => $product->id,
                    'type' => 1,
                    'quantity' => $request->quantity,
                ]);
            }, 2);//NGの時に2回試す
        }catch(Throwable $e){//Throwableで例外取得。例外情報を$eに入れる
            Log::error($e);//ログに$eを保存する(ログはstorage/logs)
            throw $e;//表示する
        }

        return redirect()
        ->route('owner.products.index')
        ->with([
            'message' =>'商品登録を実施しました',
            'status' => 'info',
        ]);
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
        $product = Product::findOrFail($id);//一つの商品
        $quantity = Stock::where('product_id', $product->id)
        ->sum('quantity');//在庫情報

        $shops = Shop::where('owner_id', Auth::id())
        ->select('id', 'name')->get();

        $images = Image::where('owner_id', Auth::id())
        ->select('id', 'title','filename')
        ->orderBy('updated_at', 'desc')->get();

        // n+1問題
        $categories = PrimaryCategory::with('secondary')->get();

        return view('owner.products.edit', 
        compact('product', 'quantity', 'shops', 'images', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        $request->validate([
            'current_quantity' => 'nullable|integer',
        ]);

        // 画面表示後に在庫数が変わっている可能性がある
        // (Edit～updateの間でユーザーが購入した場合など)
        // 在庫が同じか確認し違っていたらeditに戻す (楽観的ロックに近い)
        $product = Product::findOrFail($id);
        $quantity = Stock::where('product_id', $product->id)
        ->sum('quantity');//在庫情報

        if($request->current_quantity !== $quantity){
            $id = $request->route()->parameter('product');
            return redirect()
            ->route('owner.products.edit', ['product' => $id])
            ->with([
                'message' =>'在庫数が変更されています。再度確認してください。',
                'status' => 'alert',
            ]);;
        } else {
        //ProductとStock同時更新するためトランザクションをかけておく
        try{
            DB::transaction(function()use($request, $product){//無名関数内で親の変数を使うにはuseが必要
                $product->name = $request->name;
                $product->information = $request->information;
                $product->price = $request->price;
                $product->sort_order = $request->sort_order;
                $product->shop_id = $request->shop_id;
                $product->secondary_category_id = $request->category;
                $product->image1 = $request->image1;
                $product->is_selling =$request->is_selling;
                $product->save();

                if($request->type ===  \Constant::PRODUCT_LIST['add'] ){
                    $newQuantity = $request->quantity;
                }
                if($request->type ===  \Constant::PRODUCT_LIST['reduce'] ){
                    $newQuantity = $request->quantity * -1;
                }
                //外部キー向けのidを取得。
                //Stock::createで作成する場合はモデル側にfillableも必要
                Stock::create([
                    'product_id' => $product->id,
                    'type' => $request->type,
                    'quantity' => $newQuantity,
                ]);
            }, 2);//NGの時に2回試す
        }catch(Throwable $e){//Throwableで例外取得。例外情報を$eに入れる
            Log::error($e);//ログに$eを保存する(ログはstorage/logs)
            throw $e;//表示する
        }

        return redirect()
        ->route('owner.products.index')
        ->with([
            'message' =>'商品情報を更新しました',
            'status' => 'info',
        ]);
    }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //storageフォルダの中身を消す必要がある
        $product = Product::findOrFail($id);

        Product::findOrFail($id)->delete();//ソフトデリート
        return redirect()
        ->route('owner.products.index')
        ->with([
            'message' => '商品を削除しました。',
            'status' => 'alert',
        ]);
    }
}
