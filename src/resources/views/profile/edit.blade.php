@extends('layouts.app')

@section('title', 'プロフィール編集')

@section('content')
<div class="profile-wrap">

  <h1 class="profile-title">プロフィール編集</h1>

  {{-- バリデーションエラー --}}
  @if ($errors->any())
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  @endif

  @auth
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      {{-- 上部（アイコン + ファイル選択） --}}
      <div class="profile-top">

        <div class="profile-avatar">
          @if (!empty(auth()->user()->profile_image))
            <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="プロフィール画像">
          @endif
        </div>

        {{-- labelをボタン風にして input を隠す（CSSに合ってる） --}}
        <label class="profile-file">
          画像を選択する
          <input type="file" name="profile_image" accept="image/*">
        </label>

      </div>

      {{-- 名前 --}}
      <div class="profile-group">
        <label class="profile-label">ユーザー名</label>
        <input class="profile-input" type="text" name="name"
               value="{{ old('name', auth()->user()->name) }}">
      </div>

      {{-- 郵便番号 --}}
      <div class="profile-group">
        <label class="profile-label">郵便番号</label>
        <input class="profile-input" type="text" name="postal_code"
               value="{{ old('postal_code', auth()->user()->postal_code) }}">
      </div>

      {{-- 住所 --}}
      <div class="profile-group">
        <label class="profile-label">住所</label>
        <input class="profile-input" type="text" name="address_detail"
               value="{{ old('address_detail', auth()->user()->address_detail) }}">
      </div>

      {{-- 建物名 --}}
      <div class="profile-group">
        <label class="profile-label">建物名</label>
        <input class="profile-input" type="text" name="building"
               value="{{ old('building', auth()->user()->building ?? '') }}">
      </div>

      <div class="profile-actions">
        <button type="submit" class="profile-submit">更新する</button>
      </div>

    </form>
  @endauth

  @guest
    <p>ログインしてください。</p>
    <a href="{{ route('login') }}">ログインへ</a>
  @endguest

</div>
@endsection
