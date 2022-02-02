<p>決済ページへリダイレクトします。</p>
<script src="https://js.stripe.com/v3/"></script>
<script>
const publicKey = '{{ $publicKey }}'
const stripe = Stripe(publicKey)//publicキーを渡すことで初期化

window.onload = function() {//画面を読み込んだ時の処理
    stripe.redirectToCheckout({
        sessionId: '{{ $session->id }}'
        }).then(function (result) {
            window.location.href = '{{ route('user.cart.index') }}';
        });
}
</script>