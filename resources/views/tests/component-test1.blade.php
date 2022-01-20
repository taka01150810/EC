<x-tests.app>
    <x-slot name="header">
        ヘッダー1です。
    </x-slot>
    コンポーネントテスト1(検証すると違いがわかる)
    <x-tests.card title="タイトル" content="本文" message="$message"/>
    <x-tests.card title="タイトル" content="本文" :message="$message"/>
    {{-- 下記だとcontentとmessageがないのでエラーが出てしまう --}}
    {{-- <x-tests.card title="タイトル2"> --}}
</x-tests.app>