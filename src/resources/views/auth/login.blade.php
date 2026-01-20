@extends('layouts.app')

@section('title', 'ログイン')

@section('content')
<div class="auth-wrap">
  <div class="auth-card">
    <h1 class="auth-title">ログイン</h1>

    @if ($errors->any())
      <ul class="auth-errors">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    @endif

    <form method="POST" action="{{ route('login') }}" novalidate>
      @csrf

      <div class="auth-group">
        <label class="auth-label">メールアドレス</label>
        <input
          class="auth-input"
          type="text"
          name="email"
          value="{{ old('email') }}"
          autocomplete="username"
        >
      </div>

      <div class="auth-group">
        <label class="auth-label">パスワード</label>
        <input
          class="auth-input"
          type="password"
          name="password"
          autocomplete="current-password"
        >
      </div>

      <div class="auth-actions">
        <button class="auth-button" type="submit">ログイン</button>
      </div>

      <a class="auth-link" href="{{ route('register') }}">会員登録はこちら</a>
    </form>
  </div>
</div>
@endsection
