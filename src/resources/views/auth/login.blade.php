@extends('layouts.app')

{{-- ヘッダーをシンプルにする指示 --}}
@php $simpleHeader = true; @endphp

@section('title', 'ログイン')

@section('content')
<div class="login-box">
    <h2>ログイン</h2>
    <form method="POST" action="/login">
        @csrf
        <input type="email" name="email" placeholder="メールアドレス">
        <input type="password" name="password" placeholder="パスワード">
        <button type="submit">ログイン</button>
    </form>
</div>
@endsection
