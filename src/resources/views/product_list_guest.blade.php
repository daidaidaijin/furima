@extends('layouts.app')

@section('title', '商品一覧（ゲスト）')

@section('content')

<div class="item-list-container">

    <h2>おすすめ商品</h2>

    <div class="item-list">

        {{-- ダミーの商品カード（必要なら foreach に置き換え） --}}
        @for ($i = 0; $i < 6; $i++)
        <div class="item-card">
            <div class="item-image">商品画像</div>
            <div class="item-name">商品名</div>
        </div>
        @endfor

    </div>

</div>

@endsection
