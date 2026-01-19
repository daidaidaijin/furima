@extends('layouts.app')

@section('title', '商品一覧')

@section('content')
<div class="product-page">

  {{-- タブ（おすすめ / マイリスト） --}}
  <div class="list-tabs-wrap">
    <div class="list-tabs">
      <a href="{{ route('items.index') }}"
         class="list-tab {{ ($activeTab ?? 'recommend') === 'recommend' ? 'is-active' : '' }}">
        おすすめ
      </a>

      {{-- ゲストはログイン誘導 --}}
      <a href="{{ route('login') }}" class="list-tab">
        マイリスト
      </a>
    </div>
  </div>
  <div class="list-line"></div>

  {{-- 商品一覧 --}}
  <div class="product-grid">
    @forelse($items as $item)
      <a class="product-card" href="{{ route('items.show', $item) }}">
        <div class="product-thumb">
          @if(!empty($item->image_path))
            <img src="{{ asset('storage/' . $item->image_path) }}" alt="商品画像">
          @else
            <div class="product-thumb--dummy">商品画像</div>
          @endif
        </div>
        <p class="product-name">{{ $item->title ?? $item->name }}</p>
      </a>
    @empty
      <p class="empty-text">商品がありません。</p>
    @endforelse
  </div>

</div>
@endsection
