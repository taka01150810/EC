@php
if($name === 'image1'){ $modal = 'modal-1'; } 
if($name === 'image2'){ $modal = 'modal-2'; } 
if($name === 'image3'){ $modal = 'modal-3'; }
if($name === 'image4'){ $modal = 'modal-4'; }
//新規登録時は値がないので 値の有無を確認
$cImage = $currentImage ?? '';
$cId = $currentId ?? '';
@endphp
<div class="modal micromodal-slide" id="{{ $modal }}" aria-hidden="true">
    <div class="modal__overlay z-50" tabindex="-1" data-micromodal-close>
      <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="{{ $modal }}-title">
        <header class="modal__header">
          <h2 class="text-xl text-gray-700" id="{{ $modal }}-title">
            ファイルを選択してください
          </h2>
          <button type="button" class="modal__close" aria-label="Close modal" data-micromodal-close></button>
        </header>
        <main class="modal__content" id="{{ $modal }}-content">
            <div class="flex flex-wrap">
                @foreach ($images as $image)
                <div class="w-1/4 p-2 mb:p-4">
                    <div class="border rounded-md p-4">
                    {{-- 画像をクリックしたら画像を選択しつつモーダルを閉じる
                    JSで操作できるよう共通のCSSと個別のidや属性をつける
                    ( data-○○ とつけると、JSでe.target.dataset.○○ で取得できる)
                    PHPの変数をJSに渡す方法の一つ  --}}
                        <img class="image" data-id="{{ $name }}_{{ $image->id }}"
                        data-file="{{ $image->filename }}"
                        data-path="{{ asset('storage/products/') }}"
                        data-modal="{{ $modal }}"
                        src="{{ asset('storage/products/' . $image->filename)}}"" >
                        <div class="text-xl">{{ $image->title }}</div>
                    </div>
                    </a>
                </div>
                @endforeach
            </div>
        </main>
        <footer class="modal__footer">
          <button type="button" class="modal__btn" data-micromodal-close aria-label="閉じる">閉じる</button>
        </footer>
      </div>
    </div>
  </div>

  {{-- プレビューエリアとinputタグ(hidden) --}}
  <div class="flex justify-around items-center mb-4">
    <a class="py-2 px-4 bg-gray-200" data-micromodal-trigger="{{ $modal }}" href='javascript:;'>ファイルを選択する</a>
      <div class="w-1/4">
        <img id="{{ $name }}_thumbnail" @if($cImage) src="{{ asset('storage/products/' . $cImage)}}" @else src="" @endif> 
    </div>
  </div>
  <input id="{{ $name }}_hidden" type="hidden" name="{{ $name }}" value="{{ $cId }}">