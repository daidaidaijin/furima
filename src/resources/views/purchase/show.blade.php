@extends('layouts.app')

@section('title', '商品購入')

@section('content')
<div class="purchase">
  <div class="purchase__left">

    <div class="purchase__item">
      <div class="purchase__thumb">
        @if(!empty($item->image_path))
          <img src="{{ asset('storage/' . $item->image_path) }}" alt="商品画像">
        @else
          <div class="purchase__thumb--dummy">商品画像</div>
        @endif
      </div>

      <div class="purchase__info">
        <p class="purchase__name">{{ $item->title ?? $item->name }}</p>
        <p class="purchase__price">¥ {{ number_format($item->price) }}</p>
      </div>
    </div>

    <hr class="purchase__hr">

    {{-- 支払い方法 --}}
    <div class="purchase__block">
      <p class="purchase__label">支払い方法</p>

      <select id="payment_method" name="payment_method" class="form-select">
        <option value="">選択してください</option>
        <option value="konbini">コンビニ払い</option>
        <option value="card">カード払い</option>
      </select>
    </div>

    <hr class="purchase__hr">

    {{-- 配送先 --}}
    <div class="purchase__block">
      <div class="purchase__row">
        <p class="purchase__label">配送先</p>
        <a class="purchase__change" href="{{ route('purchase.address.edit', $item) }}">変更する</a>
      </div>

      <div class="purchase__address">
        <p>〒 {{ $shipping->postal_code ?? '未登録' }}</p>
        <p>{{ $shipping->address ?? '未登録' }}</p>
        @if(!empty($shipping->building))
            <p>{{ $shipping->building ?? '' }}</p>
        @endif
      </div>
    </div>

  </div>

  {{-- 右：小計 --}}
  <div class="purchase__right">
    <div class="purchase__summary">
      <div class="purchase__summary-row">
        <span>商品代金</span>
        <span>¥ {{ number_format($item->price) }}</span>
      </div>
      <div class="purchase__summary-row">
        <span>支払い方法</span>
        <span id="payment_text">未選択</span>
      </div>
    </div>

    <form method="POST" action="{{ route('purchase.checkout', $item) }}">
      @csrf
      <input type="hidden" name="payment_method" id="payment_hidden" value="">
      <button type="submit" class="btn-submit">購入する</button>
    </form>
  </div>
</div>

{{-- 支払い方法の反映（右側の表示&hidden） --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
  const select = document.getElementById('payment_method');
  const text = document.getElementById('payment_text');
  const hidden = document.getElementById('payment_hidden');

  const map = {
    '': '未選択',
    'konbini': 'コンビニ払い',
    'card': 'カード払い'
  };

  select.addEventListener('change', () => {
    const v = select.value;
    text.textContent = map[v] ?? '未選択';
    hidden.value = v;
  });
});
</script>
@endsection
