<h1>商品詳細</h1>

{{-- 商品画像 --}}
@if ($item->image_path)
    <img
        src="{{ asset('storage/' . $item->image_path) }}"
        style="width:300px; height:300px; object-fit:cover;"
    >
@endif

<p>商品名：{{ $item->title }}</p>
<p>価格：¥{{ number_format($item->price) }}</p>
<p>状態：{{ $item->condition }}</p>
<p>説明：{{ $item->description }}</p>

<a href="{{ route('items.index') }}">← 一覧に戻る</a>
