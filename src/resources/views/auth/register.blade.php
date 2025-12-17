@extends('layouts.app')

@section('title', '新規登録')

@section('content')
<h2>新規登録</h2>

<form method="POST" action="{{ route('register') }}">
    @csrf

    <div>
        <label>名前</label>
        <input type="text" name="name" required>
    </div>

    <div>
        <label>メールアドレス</label>
        <input type="email" name="email" required>
    </div>

    <div>
        <label>パスワード</label>
        <input type="password" name="password" required>
    </div>

    <div>
        <label>パスワード（確認）</label>
        <input type="password" name="password_confirmation" required>
    </div>

    <button type="submit">登録</button>
</form>
@endsection
