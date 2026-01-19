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

  <form method="POST" action="{{ route('purchase.address.update', $item) }}" class="address-edit__form">
    @csrf

    <label class="sell-label">郵便番号</label>
    <input type="text" name="postal_code" class="form-input"
      value="{{ old('postal_code', $shipping->postal_code ?? '') }}">

    <label class="sell-label">住所</label>
    <input type="text" name="address" class="form-input"
      value="{{ old('address', $shipping->address ?? '') }}">

    <label class="sell-label">建物名</label>
    <input type="text" name="building" class="form-input"
      value="{{ old('building', $shipping->building ?? '') }}">

    <button type="submit" class="btn-submit" style="margin-top:24px;">更新する</button>
  </form>
</div>
@endsection
