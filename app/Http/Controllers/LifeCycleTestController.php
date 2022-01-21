<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LifeCycleTestController extends Controller
{
    public function showServiceContainer(){
        //サークルコンテナに登録する
        app()->bind('lifeCycleTest', function(){
            return 'ライフサイクルテスト';
        });

        //サービスコンテナから取り出す
        $test = app()->make('lifeCycleTest');

        dd(app(), $test);
        //app() -> 中身を確認できる。bindings: array:71 -> 71個のサービスが設定されている。
        //$test -> 出力結果 ライフサイクルテスト

    }
}
