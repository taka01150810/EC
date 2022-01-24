<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Owner;//エロクアント
use Illuminate\Support\Facades\DB;//クエリビルダ
use Carbon\Carbon;

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
        //カーボン
        $date_now = Carbon::now();
        $date_parse = Carbon::parse(now());
        echo $date_now;//出力結果 現在の時間
        echo $date_parse;//出力結果 現在の時間
        //エロクアント
        $e_all = Owner::all();//返り値はEloquentCollection
        //クエリビルダ
        $q_get = DB::table('owners')->select('name','created_at')->get();//返り値はCollection
        $q_first = DB::table('owners')->select('name')->first();//返り値はstdClass
        //コレクション
        $c_test = collect([//返り値はCollection
            'name' => 'テスト'
        ]);

        // dd($e_all, $q_get, $q_first, $c_test);

        return view('admin.owners.index',
        compact('e_all', 'q_get'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
