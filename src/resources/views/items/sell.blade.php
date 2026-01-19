@extends('layouts.app')

@section('title', '商品出品')

@section('content')

<div class="sell-container">

    <h2 class="sell-title">商品の出品</h2>

    {{-- エラー表示 --}}
    @if ($errors->any())
        <ul class="sell-errors">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- 商品画像 --}}
        <section class="sell-section">
            <h4 class="sell-section__title">商品画像</h4>
            <div class="sell-image-box">
                <input type="file" name="image" required>
            </div>
        </section>

        {{-- 商品の詳細 --}}
        <section class="sell-section">
            <h4 class="sell-section__title sell-section__title--line">商品の詳細</h4>

            {{-- カテゴリ --}}
            <label class="sell-label">カテゴリー</label>
            <div class="category-pills">
                @foreach($categories as $category)
                    <label class="category-pill">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}">
                        {{ $category->name }}
                    </label>
                @endforeach
            </div>

            {{-- 商品状態 --}}
            <label class="sell-label">商品の状態</label>
            <select name="condition" required class="form-select">
                <option value="">選択してください</option>
                <option value="新品・未使用">新品・未使用</option>
                <option value="未使用に近い">未使用に近い</option>
                <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
                <option value="やや傷や汚れあり">やや傷や汚れあり</option>
                <option value="傷や汚れあり">傷や汚れあり</option>
            </select>
        </section>

        {{-- 商品名と説明 --}}
        <section class="sell-section">
            <h4 class="sell-section__title sell-section__title--line">商品名と説明</h4>

            <label class="sell-label">商品名</label>
            <input type="text" name="title" required class="form-input">

            <label class="sell-label">ブランド名</label>
            <input type="text" name="brand" class="form-input">

            <label class="sell-label">商品の説明</label>
            <textarea name="description" required rows="4" class="form-textarea"></textarea>
        </section>

        {{-- 販売価格 --}}
        <label class="sell-label">販売価格</label>

        <div class="price-input">
            <span class="price-input__yen">¥</span>
            <input
                type="number"
                name="price"
                required
                class="form-input price-input__field"
            >
        </div>


        {{-- 出品ボタン --}}
        <button type="submit" class="btn-submit">
            出品する
        </button>

    </form>
</div>

@endsection
