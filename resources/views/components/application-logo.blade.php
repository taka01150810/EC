<div class="w-8">
    {{-- publicフォルダのimagesを指定している。public/imagesには初期画像。
    storage/app/publicはアップロードされる画像などを保管する。 --}}
    {{-- 本来はblade側でCSSは調整する --}}
    <img src="{{ asset("images/logo.png") }}">
</div>