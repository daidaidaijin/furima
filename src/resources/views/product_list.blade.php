<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧ページ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>

<body>

<header class="site-header">
  <div class="header-inner">

    <div class="header-logo"></div>

    <div class="header-search">
      <form action="/search" method="GET">
        <input type="text" name="keyword" placeholder="なにをお探しですか？">
      </form>
    </div>

    <nav class="header-nav">
      <ul>
        <li><a href="/logout">ログアウト</a></li>
        <li><a href="/mypage">マイページ</a></li>
        <li class="sell"><a href="/sell">出品</a></li>
      </ul>
    </nav>

  </div>
</header>

</body>
</html>
