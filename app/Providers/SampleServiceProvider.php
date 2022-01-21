<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SampleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //サービスを登録する
        app()->bind('serviceProvider', function(){
            return 'サービスプロバイダのテスト';
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //全サービスプロバイダーを読み込む後に実行したいコード
    }
}
