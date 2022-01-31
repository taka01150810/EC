<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            カート
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(count($products) > 0)
                    @foreach($products as $product)
                    <div class="md:flex md:items-center">
                    <div class="md:w-3/12">
                        @if($product->shop->filename !== null)
                        <img class="mx-auto w-40 h-40 object-cover" src="{{ asset('storage/shops/' . $product->shop->filename) }}">
                        @else
                        <img class="mx-auto w-40 h-40 object-cover"src="">
                        @endif
                    </div>
                    <div class="md:w-4/12 md:ml-2">{{ $product->name }}</div>
                    <div class="md:w-3/12 flex justify-around">
                        <div>{{ $product->pivot->quantity}}</div>
                        <div>{{ number_format($product->pivot->quantity * $product->price )}}<span class="text-sm text-gray-700">円（税込）</span></div>
                    </div>
                    @endforeach
                    合計金額: {{ $totalPrice }}
                   @else
                    カートに商品が入っていません。
                   @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
