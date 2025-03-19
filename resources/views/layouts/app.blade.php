<!DOCTYPE html>
<html lang="{{str_replace('_', '-', app()->getLocale())}}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{csrf_token()}}">

    <title>{{config('app.name', 'COACHTECHフリマ')}} - @yield('title', 'ホーム')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('css/reset.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">

    @yield('styles')

    <script src="{{asset('js/app.js')}}" defer></script>
</head>

<body>
    <div class="app-container">
        <header class="app-header">
            <div class="header-inner">
                <div class="header-logo">
                    <a href="{{route('home')}}" class="logo-link">
                        <h1 class="logo-text">COACHTECHフリマ</h1>
                    </a>
                </div>

                <div class="header-search">
                    <form action="{{route('items.index')}}" method="GET" class="search-form">
                        <input type="text" name="search" class="search-input" placeholder="何をお探しですか？"
                            value="{{request('search')}}">
                        <button class="search-button" type="submit">検索</button>
                    </form>
                </div>

                <nav class="header-nav">
                    <ul class="nav-list">
                        @guest
                            <li class="nav-item">
                                <a href="{{route('login')}}" class="nav-link">ログイン</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('register')}}" class="nav-link">会員登録</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{route('items.create')}}" class="nav-link nav-button">出品する</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" id="navbarDropdown">
                                    {{Auth::user()->name}}
                                </a>
                                <div class="dropdown-menu">
                                    <a href="{{route('profile.show', Auth::user())}}" class="dropdown-item">マイページ</a>
                                    <a href="{{route('items.myItems')}}" class="dropdown-item">出品した商品</a>
                                    <a href="{{route('purchases.index')}}" class="dropdown-item">購入した商品</a>
                                    <a href="{{route('likes.index')}}" class="dropdown-item">いいねした商品</a>
                                    <a href="{{route('profile.edit')}}" class="dropdown-item">プロフィール編集</a>
                                    <div class="dropdown-divider"></div>
                                    <form action="{{route('logout')}}" method="POST">
                                        @csrf
                                        <button class="dropdown-item" type="submit">ログアウト</button>
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </nav>
            </div>
        </header>

        <main class="app-main">
            @if (session('success'))
                <div class="alert alert-success">
                    {{session('success')}}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{session('error')}}
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="app-footer">
            <div class="footer-inner">
                <div class="footer-links">
                    <div class="footer-section">
                        <h3 class="footer-heading">COACHTECHフリマについて</h3>
                        <ul class="footer-list">
                            <li><a href="#" class="footer-link">会社概要</a></li>
                            <li><a href="#" class="footer-link">採用情報</a></li>
                            <li><a href="#" class="footer-link">プレスリリース</a></li>
                        </ul>
                    </div>

                    <div class="footer-section">
                        <h3 class="footer-heading">ヘルプ＆ガイド</h3>
                        <ul class="footer-list">
                            <li><a href="#" class="footer-link">ご利用ガイド</a></li>
                            <li><a href="#" class="footer-link">お問い合わせ</a></li>
                            <li><a href="#" class="footer-link">よくある質問</a></li>
                        </ul>
                    </div>

                    <div class="footer-section">
                        <h3 class="footer-heading">プライバシーと利用規約</h3>
                        <ul class="footer-list">
                            <li><a href="#" class="footer-link">プライバシーポリシー</a></li>
                            <li><a href="#" class="footer-link">利用規約</a></li>
                            <li><a href="#" class="footer-link">特定商取引法に基づく表記</a></li>
                        </ul>
                    </div>
                </div>

                <div class="footer-bottom">
                    <p class="copyright">&copy; {{date('Y')}} COACHTECHフリマ All Rights Reserved</p>
                </div>
            </div>
        </footer>
    </div>

    @yield('scripts')

</body>

</html>