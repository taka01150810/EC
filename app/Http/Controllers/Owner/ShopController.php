<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;
use Illuminate\Support\Facades\Storage;
use InterventionImage;
use App\Http\Requests\UploadImageRequest;
use App\Services\ImageService;

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

    public function update(UploadImageRequest $request, $id)
    {
        //リサイズしないパターン
        $imageFile = $request->image; //一時保存
        if(!is_null($imageFile) && $imageFile->isValid() ){
            //写真のアップロード関連はApp\Services\ImageService; にあります。
            $fileNameToStore = ImageService::upload($imageFile, 'shops');
        }

        return redirect()->route('owner.shops.index');
    }
}
