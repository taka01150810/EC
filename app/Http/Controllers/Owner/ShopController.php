<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;
use Illuminate\Support\Facades\Storage;
use InterventionImage;

class ShopController extends Controller
{
    //ログイン済みのユーザーのみ表示させるためコンストラクタで下記を設定
    public function __construct()
    {
        $this->middleware('auth:owners');
        // owner/shops/edit/2/で数字を変更すると
        // 他のオーナーのShopが見れてしまうので
        // ログイン済みのオーナーのShop URLでなければ404画面を表示
        $this->middleware(function($request, $next){
            $id = $request->route()->parameter('shop'); //shopのid取得
            if(!is_null($id)){ //null判定
                $shopsOwnerId = Shop::findOrFail($id)->owner->id;
                $shopId = (int)$shopsOwnerId; // キャスト 文字列→数値に型変換
                $ownerId = Auth::id();
            if($shopId !== $ownerId){ // 同じでなかったら
                abort(404); // 404画面表示
            }
        }
            return $next($request);
        });
    }

    public function index()
    {
        $ownerId = Auth::id();//認証されているID
        $shops = Shop::where('owner_id', $ownerId)->get();
        return view('owner.shops.index',compact('shops'));
    }

    public function edit($id)
    {
        $shop = Shop::findOrFail($id);
        return view('owner.shops.edit', compact('shop'));
    }

    public function update(Request $request, $id)
    {
        //リサイズしないパターン
        $imageFile = $request->image; //一時保存
        if(!is_null($imageFile) && $imageFile->isValid() ){
            // Storage::putFile('public/shops', $imageFile);//putFileでファイル名を生成。サーバー側に保存。
            $fileName = uniqid(rand().'_');//ランダムなファイル名にする
            $extension = $imageFile->extension();//拡張子を取得
            $fileNameToStore = $fileName. '.' . $extension;
            $resizedImage = InterventionImage::make($imageFile)->resize(1920, 1080)->encode();

            Storage::put('public/shops/' . $fileNameToStore, $resizedImage );
        }

        return redirect()->route('owner.shops.index');
    }
}
