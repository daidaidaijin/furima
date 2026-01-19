@extends('layouts.app')

@section('title', '商品詳細')

@section('content')
<div class="item-show">
  <div class="item-show__inner">

    {{-- 左：画像 --}}
    <div class="item-show__media">
      @if(!empty($item->image_path))
        <img class="item-show__img" src="{{ asset('storage/' . $item->image_path) }}" alt="商品画像">
      @else
        <div class="item-show__img item-show__img--dummy">商品画像</div>
      @endif
    </div>

    {{-- 右：情報 --}}
    <div class="item-show__info">
      <h1 class="item-show__name">{{ $item->title ?? $item->name }}</h1>
      <p class="item-show__brand">{{ $item->brand ?? 'ブランド名' }}</p>

      <p class="item-show__price">
        ¥{{ number_format($item->price) }} <span>(税込)</span>
      </p>

      {{-- いいね / コメント数 --}}
      <div class="item-show__actions">
        {{-- いいね --}}
        @auth
          <form method="POST" action="{{ route('items.like', $item) }}">
            @csrf
            <button type="submit" class="like-btn {{ ($isLiked ?? false) ? 'is-liked' : '' }}">♥</button>
          </form>
        @else
          <a class="like-btn" href="{{ route('login') }}">♥</a>
        @endauth
        <span class="count">{{ $item->likes_count }}</span>

        {{-- コメント数 --}}
        <span class="comment-icon">💬</span>
        <span class="count">{{ $item->comments_count }}</span>
      </div>

      {{-- 購入動線 --}}
      <div class="item-show__buy">
        @auth
          {{-- purchase.show がまだ無いなら、一旦 # にしてOK --}}
          <a class="buy-btn" href="{{ route('purchase.show', $item) }}">購入手続きへ</a>
        @else
          <a class="buy-btn" href="{{ route('login') }}">購入手続きへ</a>
        @endauth
      </div>

      {{-- 商品説明 --}}
      <div class="item-show__section">
        <h2 class="item-show__sectionTitle">商品説明</h2>
        <p class="item-show__text">{{ $item->description }}</p>
      </div>

      {{-- 商品の情報 --}}
      <div class="item-show__section">
        <h2 class="item-show__sectionTitle">商品の情報</h2>

        <div class="item-show__table">
          <div class="row">
            <div class="th">カテゴリー</div>
            <div class="td">
              @forelse($item->categories ?? [] as $cat)
                <span class="tag">{{ $cat->name }}</span>
              @empty
                <span class="tag">未設定</span>
              @endforelse
            </div>
          </div>

          <div class="row">
            <div class="th">商品の状態</div>
            <div class="td">{{ $item->condition ?? '未設定' }}</div>
          </div>
        </div>
      </div>

    </div>
  </div>

  {{-- コメント --}}
  <div class="item-show__comments">
    <h2 class="item-show__commentsTitle">コメント({{ $item->comments_count }})</h2>

    {{-- 一覧 --}}
    @forelse($item->comments as $c)
      <div class="comment-row">
        <div class="comment-user">
          <div class="comment-avatar"></div>
          <p class="comment-name">{{ $c->user->name ?? 'user' }}</p>
        </div>
        <p class="comment-body">{{ $c->comment }}</p>
      </div>
    @empty
      <p class="comment-empty">まだコメントはありません。</p>
    @endforelse

    {{-- 投稿フォーム --}}
    @auth
      <form method="POST" action="{{ route('items.comments.store', $item) }}" class="comment-form">
        @csrf

        <label class="comment-label">商品へのコメント</label>

        <textarea class="comment-textarea" name="comment" maxlength="255" placeholder="コメントを入力">{{ old('comment') }}</textarea>

        @error('comment')
          <p class="error">{{ $message }}</p>
        @enderror

        <button type="submit" class="comment-submit">コメントを送信する</button>
      </form>
    @else
      <p class="need-login">コメントするにはログインが必要です。</p>
      <a href="{{ route('login') }}" class="comment-submit">ログインへ</a>
    @endauth
  </div>

</div>
@endsection
