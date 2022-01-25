<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;

class ShopController extends Controller
{
    //ログイン済みのユーザーのみ表示させるためコンストラクタで下記を設定
    public function __construct()
    {
        $this->middleware('auth:owners');
    }

    public function index()
    {
        $ownerId = Auth::id();//認証されているID
        $shops = Shop::where('owner_id', $ownerId)->get();
        return view('owner.shops.index',compact('shops'));
    }

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {

    }
}
