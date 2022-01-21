<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LifeCycleTestController extends Controller
{
    public function showServiceProvider(){
        $encrypt = app()->make('encrypter');
        $password = $encrypt->encrypt('password');//encryptで暗号化
        dd($password, $encrypt->decrypt($password));//decryptで元に戻る
    }

    public function showServiceContainer(){
        //サークルコンテナに登録する
        app()->bind('lifeCycleTest', function(){
            return 'ライフサイクルテスト';
        });

        //サービスコンテナから取り出す
        $test = app()->make('lifeCycleTest');

        //サービスコンテナなしのパターン
        $message = new Message();
        $sample = new Sample($message);
        $sample->run();//出力結果 メッセージ表示

        //サービスコンテナありのパターン
        app()->bind('sample', Sample::class);//サークルコンテナに登録する
        $sample = app()->make('sample');//サービスコンテナから取り出す
        $sample->run();//出力結果 メッセージ表示

        dd(app(), $test);
        //app() -> 中身を確認できる。bindings: array:71 -> 71個のサービスが設定されている。
        //$test -> 出力結果 ライフサイクルテスト

    }
}
class Sample
{
    public $message;
    public function __construct(Message $message){
        $this->message = $message;
    }
    public function run(){
        $this->message->send();
    }
}

class Message
{
    public function send(){
        echo('メッセージ表示');
    }
}
