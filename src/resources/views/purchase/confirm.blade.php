@extends('layouts.app')

@section('title', '購入確認')

@section('content')
<div class="sell-container">
  <h2 class="sell-title">購入確認</h2>

  <div style="border:1px solid #e5e5e5; padding:16px; border-radius:6px;">
    <p><strong>{{ $item->name ?? $item->title }}</strong></p>
    <p>価格：¥{{ number_format($item->price) }}</p>
  </div>

  <form action="{{ route('purchase.store', $item) }}" method="POST" style="margin-top:16px;">
    @csrf
    <button type="submit" class="btn-submit">購入を確定する</button>
  </form>

  <div style="margin-top:12px;">
    <a href="{{ route('items.show', $item) }}">戻る</a>
  </div>
</div>
@endsection
