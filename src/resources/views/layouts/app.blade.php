<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>フリマアプリ</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>
<body>
    <header class="header">
        <div class="header__inner">
            <a class="header__logo" href="/">
                <!-- 画像 -->
                <img src="{{ asset('images/logo.png') }}" alt="COACHTECH" class="header__logo-image">
            </a>

            {{-- ログイン済みの場合だけ表示 --}}
            @auth
                <nav class="header__nav">
                    <form class="header__logout-form" action="/logout" method="post">
                        @csrf
                        <button class="header__logout-button" type="submit">
                            ログアウト
                        </button>
                    </form>
                    <a class="header__link" href="/mypage">マイページ</a>
                    <a class="header__sell-link" href="/sell">出品</a>
                </nav>
            @endauth

            {{-- 修正: 未ログイン かつ ログイン/会員登録画面以外の場合だけ表示 --}}
            @guest
                @if (!request()->is('login') && !request()->is('register'))
                    <nav class="header__nav">
                        <a class="header__link" href="/login">ログイン</a>
                        <a class="header__link" href="/register">会員登録</a>
                    </nav>
                @endif
            @endguest
        </div>
    </header>

    <main class="main">
        @yield('content')
    </main>
</body>
</html>