@extends('layouts.app')

@section('title', '送付先住所の変更')

@section('content')
<div class="address-edit">
  <h2 class="address-edit__title">送付先住所の変更</h2>

  @if ($errors->any())
    <ul style="color:red; margin: 0 auto 20px; max-width:600px;">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  @endif

  <form method="POST" action="{{ route('purchase.address.update', $item) }}" novalidate>
  @csrf

  <label>郵便番号</label>
  <input type="text" name="postal_code" value="{{ old('postal_code', $shipping->postal_code ?? '') }}" placeholder="123-4567">

  @error('postal_code')
    <p class="error">{{ $message }}</p>
  @enderror

  <label>住所</label>
  <input type="text" name="address" value="{{ old('address', $shipping->address ?? '') }}">

  @error('address')
    <p class="error">{{ $message }}</p>
  @enderror

  <label>建物名</label>
  <input type="text" name="building" value="{{ old('building', $shipping->building ?? '') }}">

  <button type="submit">更新する</button>
</form>

</div>
@endsection
