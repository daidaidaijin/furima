@extends('layouts.app')

@section('title', 'マイページ')

@section('content')
<div class="mypage">

  @auth
    {{-- 上部プロフィール行（アイコン / 名前 / 編集ボタン） --}}
    <div class="mypage__profile">

      {{-- アイコン --}}
      @if (!empty(auth()->user()->profile_image))
        <div class="mypage__avatar">
          <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="プロフィール画像">
        </div>
      @else
        <div class="mypage__avatar--dummy"></div>
      @endif

      {{-- 名前 --}}
      <div class="mypage__name">{{ auth()->user()->name }}</div>

      {{-- 編集ボタン --}}
      @if (auth()->user()->hasVerifiedEmail())
        <a href="{{ route('profile.edit') }}" class="mypage__edit">プロフィールを編集</a>
      @else
        <a href="{{ route('verification.notice') }}" class="mypage__edit">プロフィールを編集</a>
      @endif
    </div>

    {{-- タブ（クエリで切替） --}}
    <div class="mypage__tabs">
      <a href="{{ route('mypage', ['tab' => 'sell']) }}"
         class="mypage__tab {{ ($tab ?? 'sell') === 'sell' ? 'is-active' : '' }}">
        出品した商品
      </a>

      <a href="{{ route('mypage', ['tab' => 'buy']) }}"
         class="mypage__tab {{ ($tab ?? 'sell') === 'buy' ? 'is-active' : '' }}">
        購入した商品
      </a>
    </div>

    <div class="mypage__line"></div>

    {{-- 商品一覧 --}}
    <div class="mypage__list">

      @php
        $showItems = (($tab ?? 'sell') === 'buy') ? $purchasedItems : $sellingItems;
      @endphp

      @forelse($showItems as $item)
        <a href="{{ route('items.show', $item) }}" class="mypage__card">
          <div class="mypage__thumb">
            @if (!empty($item->image_path))
              <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title ?? $item->name }}">
            @else
              <div class="mypage__thumb--dummy"></div>
            @endif

            {{-- SOLDOUT 表示（出品/購入どっちでも見えるように） --}}
            @if(!empty($item->is_sold))
              <div class="mypage__sold">SOLD</div>
            @endif
          </div>
        </a>
      @empty
        <p>表示する商品がありません。</p>
      @endforelse

    </div>

  @endauth

  @guest
    <p>ログインしてください。</p>
    <a href="{{ route('login') }}">ログインへ</a>
  @endguest

</div>
@endsection
