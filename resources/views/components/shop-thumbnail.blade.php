@if(empty($filename))
{{-- 初期設定の画像は publicフォルダに入る --}}
<img src="{{ asset('images/no_image.jpg')}}">
@else
{{-- アップロードされる画像は storageフォルダに入る --}}
<img src="{{ asset('storage/shops/' . $filename) }}">
@endif