<x-tests.app>
    <x-slot name="header">
        ヘッダー2です。
    </x-slot>
    コンポーネントテスト2(検証すると違いがわかる)
    <x-test-class-base classBaseMessage="メッセージです"/>
    <div class="mb-4"></div>
        <x-test-class-base classBaseMessage="属性コンポーネントのメッセージです" defaultMessage="初期値から変更しています"/>
</x-tests.app>