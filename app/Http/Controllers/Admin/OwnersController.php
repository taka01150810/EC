<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Owner;//エロクアント
use App\Models\Shop;//エロクアント
use Illuminate\Support\Facades\DB;//クエリビルダ
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Throwable;
use Illuminate\Support\Facades\Log;

class OwnersController extends Controller
{

    //ログイン済みのユーザーのみ表示させるためコンストラクタで下記を設定
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $owners = Owner::select('id','name', 'email', 'created_at')
        ->paginate(10);
        // 元々ページネーションはvendorファイル内にあるが、composerのアップデートにより書き変わる可能性があるので
        // php artisan vendor publish --tag=laravel-paginationによりvendorフォルダをコピーする

        return view('admin.owners.index',
        compact('owners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.owners.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)//メソッドの引数に依存対象のクラスを渡すメソッド・インジェクション
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:owners'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        //複数のテーブル(ownerとshop)に保存する際にはトランザクションをかける
        try{
            DB::transaction(function()use($request){//無名関数内で親の変数を使うにはuseが必要
                $owner = Owner::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                //外部キー向けのidを取得。
                //Shop::createで作成する場合はモデル側にfillableも必要
                Shop::create([
                    'owner_id' => $owner->id,
                    'name' => '店名を入力してください',
                    'information' => '',
                    'filename' => '',
                    'is_selling' => true
                ]);
            }, 2);//NGの時に2回試す
        }catch(Throwable $e){//Throwableで例外取得。例外情報を$eに入れる
            Log::error($e);//ログに$eを保存する(ログはstorage/logs)
            throw $e;//表示する
        }

        return redirect()
        ->route('admin.owners.index')
        ->with([
            'message' =>'オーナー登録を実施しました',
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
        $owner = Owner::findOrFail($id);//idがなければ404画面
        return view('admin.owners.edit', compact('owner'));
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
        $owner = Owner::findOrFail($id);
        $owner->name = $request->name;
        $owner->email = $request->email;
        $owner->password = Hash::make($request->password);
        $owner->save();

        return redirect()
        ->route('admin.owners.index')
        ->with([
            'message' => 'オーナー情報を更新しました',
            'status' => 'info',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Owner::findOrFail($id)->delete();//ソフトデリート
        return redirect()
        ->route('admin.owners.index')
        ->with([
            'message' => 'オーナー情報を削除しました。',
            'status' => 'alert',
        ]);
    }

    public function expiredOwnerIndex(){
        $expiredOwners = Owner::onlyTrashed()->get();//ゴミ箱のみ表示 withTrashedだったらゴミ箱も含めて表示
        return view('admin.expired-owners',compact('expiredOwners'));
    }
    
    public function expiredOwnerDestroy($id){
        Owner::onlyTrashed()->findOrFail($id)->forceDelete();//完全に削除
        return redirect()->route('admin.expired-owners.index');
    }
}
