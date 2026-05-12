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
            <a class="header__logo" href="{{ route('products.index') }}">
                <!-- 画像 -->
                <img src="{{ asset('images/logo.png') }}" alt="COACHTECH" class="header__logo-image">
            </a>


            {{--ログイン画面・会員登録画面・メール認証以外で検索窓を表示--}}
            @if (
                !request()->is('login') && 
                !request()->is('register')&& 
                !request()->routeIs('verification.notice')
                )
                <form class="header__search-form" action="{{ route('products.index') }}" method="GET">
                    <input
                        class="header__search-input"
                        type="text"
                        name="keyword"
                        placeholder="なにをお探しですか？"
                        value="{{ request('keyword') }}"
                    >
                </form>
            @endif



            {{-- 修正: メール認証誘導画面ではナビを表示しない --}}
            @if (!request()->routeIs('verification.notice'))
                {{--ログイン済みの場合だけ表示（ログアウト、マイページ、出品）--}}
                @auth
                    <nav class="header__nav">
                        <form class="header__logout-form" action="/logout" method="post">
                            @csrf
                            <button class="header__logout-button" type="submit">
                                ログアウト
                            </button>
                        </form>
                        <a class="header__link" href="{{ route('profile.show') }}">マイページ</a>
                        <a class="header__sell-link" href="/sell">出品</a>
                    </nav>
                @endauth

                {{--未ログイン かつ ログイン/会員登録画面以外の場合だけ表示（ログイン、マイページ、出品） --}}
                @guest
                    @if (!request()->is('login') && !request()->is('register'))
                        <nav class="header__nav">
                            <a class="header__link" href="{{ route('login') }}">ログイン</a>
                            {{--未ログイン時は会員登録へ --}}
                            <a class="header__link" href="{{ route('register') }}">マイページ</a>
                            {{--未ログイン時は会員登録へ --}}
                            <a class="header__sell-link" href="{{ route('register') }}">出品</a>
                        </nav>
                    @endif
                @endguest
            @endif
        </div>
    </header>

    <main class="main">
        @yield('content')
    </main>
</body>
</html>