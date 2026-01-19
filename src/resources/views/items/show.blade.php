@extends('layouts.app')

@section('title', '商品詳細')

@section('content')
<div class="item-show">

  {{-- 上段（画像：左 / 情報：右） --}}
  <div class="item-show__top">

    {{-- 左：商品画像 --}}
    <div class="item-show__image">
      @if(!empty($item->image_path))
        <img src="{{ asset('storage/' . $item->image_path) }}" alt="商品画像">
      @else
        <div class="item-show__image--dummy">商品画像</div>
      @endif
    </div>

    {{-- 右：商品情報 --}}
    <div class="item-show__info">

      {{-- name/title どっちでも表示できるように --}}
      <h1 class="item-show__name">{{ $item->title ?? $item->name }}</h1>

      <p class="item-show__brand">{{ $item->brand ?? '' }}</p>

      <p class="item-show__price">
        ¥{{ number_format($item->price) }} <span>(税込)</span>
      </p>

      {{-- フラッシュメッセージ（購入完了など） --}}
      @if(session('message'))
        <p style="margin-top:10px; color:green; font-weight:bold;">
          {{ session('message') }}
        </p>
      @endif

      {{-- アクション（いいね/コメント） --}}
      <div class="item-show__actions">

        {{-- いいね --}}
        @auth
          <form method="POST" action="{{ route('items.like', $item) }}">
            @csrf
            <button type="submit" class="icon-btn like-btn" aria-label="いいね">
              <img
                src="{{ asset($isLiked ? 'images/heart_active.png' : 'images/heart_default.png') }}"
                alt="いいね">
            </button>
          </form>
        @else
          <a href="{{ route('login') }}" class="icon-btn like-btn" aria-label="いいね">
            <img src="{{ asset('images/heart_default.png') }}" alt="いいね">
          </a>
        @endauth

        <span class="count">{{ $item->likes_count }}</span>

        {{-- コメント数 --}}
        <div class="icon-wrap">
          <img src="{{ asset('images/comment.png') }}" alt="コメント" class="comment-icon">
          <span class="count">{{ $item->comments_count }}</span>
        </div>

      </div>

      {{-- ===== 購入エリア（完全版） ===== --}}
      <div style="margin-top:16px;">

        {{-- 1) 売り切れ --}}
        @if(!empty($item->is_sold) && $item->is_sold)
          <div class="buy-btn" style="background:#999; cursor:not-allowed; text-align:center;">
            SOLD OUT
          </div>

        @else
          {{-- 2) ログインしていない --}}
          @guest
            <a href="{{ route('login') }}" class="buy-btn">ログインして購入</a>

          @else
            {{-- 3) ログインしてるけど自分の商品 --}}
            @if(auth()->id() === $item->user_id)
              <div class="buy-btn" style="background:#bbb; cursor:not-allowed; text-align:center;">
                自分の商品は購入できません
              </div>

            {{-- 4) 購入できる --}}
            @else
              <a href="{{ route('purchase.show', $item) }}" class="buy-btn">
                購入手続きへ
              </a>
            @endif
          @endguest
        @endif

      </div>

      {{-- 商品説明 --}}
      <div class="item-show__desc">
        <h2>商品説明</h2>
        <p>{{ $item->description }}</p>
      </div>

      {{-- 商品情報 --}}
      <div class="item-show__meta">
        <h2>商品の情報</h2>
        <dl>
          <dt>カテゴリー</dt>
          <dd>
            @forelse($item->categories as $cat)
              <span class="tag">{{ $cat->name }}</span>
            @empty
              <span class="tag">未設定</span>
            @endforelse
          </dd>

          <dt>商品の状態</dt>
          <dd>{{ $item->condition ?? '未設定' }}</dd>
        </dl>
      </div>

    </div>
  </div>

  {{-- コメント（右カラム下に配置するためのラッパー） --}}
  <div class="item-show__comments-area">
    <div class="item-show__comments">
      <h2>コメント({{ $item->comments_count }})</h2>

      {{-- コメント一覧 --}}
      @forelse($item->comments as $c)
        <div class="comment-row">
          <div class="comment-user">
            <div class="comment-avatar"></div>
            <p class="comment-name">{{ $c->user->name ?? 'user' }}</p>
          </div>
          <p class="comment-body">{{ $c->comment }}</p>
        </div>
      @empty
        <p class="empty-text">コメントはまだありません。</p>
      @endforelse

      {{-- 投稿フォーム --}}
      @auth
        <form method="POST" action="{{ route('items.comments.store', $item) }}" class="comment-form">
          @csrf

          <label class="comment-label">商品へのコメント</label>

          <textarea name="comment" placeholder="商品へのコメント">{{ old('comment') }}</textarea>

          @error('comment')
            <p class="error">{{ $message }}</p>
          @enderror

          <button type="submit" class="comment-submit">コメントを送信する</button>
        </form>
      @else
        <p class="need-login">コメントするにはログインが必要です。</p>
        <a href="{{ route('login') }}" class="comment-submit">ログインしてコメントする</a>
      @endauth

    </div>
  </div>

</div>
@endsection
