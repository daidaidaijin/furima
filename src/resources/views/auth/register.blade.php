@extends('layouts.app')

@section('title', '会員登録')

@section('content')
<div class="auth-wrap">
  <div class="auth-card">
    <h1 class="auth-title">会員登録</h1>

    @if ($errors->any())
      <ul class="auth-errors">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    @endif

    <form method="POST" action="{{ route('register') }}" novalidate>
      @csrf

      <div class="auth-group">
        <label class="auth-label">ユーザー名</label>
        <input class="auth-input" type="text" name="name" value="{{ old('name') }}" required>
      </div>

      <div class="auth-group">
        <label class="auth-label">メールアドレス</label>
        <input class="auth-input" type="email" name="email" value="{{ old('email') }}" required>
      </div>

      <div class="auth-group">
        <label class="auth-label">パスワード</label>
        <input class="auth-input" type="password" name="password" required>
      </div>

      <div class="auth-group">
        <label class="auth-label">確認用パスワード</label>
        <input class="auth-input" type="password" name="password_confirmation" required>
      </div>

      <div class="auth-actions">
        <button class="auth-button" type="submit">登録する</button>
      </div>

      <a class="auth-link" href="{{ route('login') }}">ログインはこちら</a>
    </form>
  </div>
</div>
@endsection
