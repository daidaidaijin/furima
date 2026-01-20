<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
    <link rel="stylesheet" href="{{ asset('css/item_show.css') }}">
</head>

<body>
@php
    // ヘッダーを簡略表示（ロゴのみ）にするページ
    $authPages = [
        'login',
        'register',
        'verification.*',
        'password.*',
    ];
@endphp

<header class="site-header">
    <div class="header-inner">

        {{-- ロゴ（常に表示） --}}
        <a href="{{ route('items.index') }}" class="header-logo-link">
            <div class="header-logo"></div>
        </a>

        {{-- 認証系ページ以外 --}}
        @if (!request()->routeIs($authPages))

            {{-- 検索 --}}
            <div class="header-search">
                <form action="{{ route('items.index') }}" method="GET">
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="なにをお探しですか？"
                    >
                </form>
            </div>

            {{-- ナビ --}}
            <nav class="header-nav">
                <ul>

                    {{-- ログイン / ログアウト --}}
                    @guest
                        <li><a href="{{ route('login') }}">ログイン</a></li>
                    @endguest

                    @auth
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="logout-button">ログアウト</button>
                            </form>
                        </li>
                    @endauth

                    {{-- マイページ --}}
                    <li>
                        @auth
                            <a href="{{ route('mypage') }}">マイページ</a>
                        @else
                            <a href="{{ route('login') }}">マイページ</a>
                        @endauth
                    </li>

                    {{-- 出品 --}}
                    <li class="sell">
                        @guest
                            <a href="{{ route('login') }}">出品</a>
                        @else
                            <a href="{{ route('items.sell') }}">出品</a>
                        @endguest
                    </li>

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
