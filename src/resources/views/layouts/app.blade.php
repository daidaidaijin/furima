<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>

<body>

<header class="site-header">
  <div class="header-inner">

    {{-- ロゴ --}}
    <div class="header-logo"></div>

    {{-- 検索フォーム（ログインページでは非表示にする） --}}
    @if (!Route::is('login'))
    <div class="header-search">
      <form action="/search" method="GET">
        <input type="text" name="keyword" placeholder="なにをお探しですか？">
      </form>
    </div>
    @endif

    {{-- ナビゲーション --}}
    @if (!Route::is('login'))
    <nav class="header-nav">
  <ul>
    {{-- ログイン前：一番左にログイン --}}
    @guest
      <li><a href="/login">ログイン</a></li>
    @endguest

    {{-- ログイン後：一番左にログアウト --}}
    @auth
      <li>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="logout-button">ログアウト</button>
        </form>
      </li>
    @endauth

    {{-- 共通：マイページ・出品 --}}
    <li><a href="/mypage">マイページ</a></li>
    <li class="sell"><a href="/sell">出品</a></li>
  </ul>
</nav>
    @endif

  </div>
</header>

<main>
    @yield('content')
</main>

</body>
</html>
