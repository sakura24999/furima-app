@extends('layouts.app')

@section('title', '会員登録')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
    <div class="auth-container">
        <h2 class="auth-title">会員登録</h2>

        <form method="POST" action="{{ route('register') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">ユーザー名</label>
                <input id="name" type="text" class="form-input @error('name') is-invalid @enderror" name="name"
                    value="{{ old('name') }}" required autocomplete="name" autofocus>

                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">メールアドレス</label>
                <input id="email" type="email" class="form-input @error('email') is-invalid @enderror" name="email"
                    value="{{ old('email') }}" required autocomplete="email">

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">パスワード</label>
                <input id="password" type="password" class="form-input @error('password') is-invalid @enderror"
                    name="password" required autocomplete="new-password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password-confirm" class="form-label">確認用パスワード</label>
                <input id="password-confirm" type="password" class="form-input" name="password_confirmation" required
                    autocomplete="new-password">
            </div>

            <div class="form-button">
                <button type="submit" class="auth-button">
                    登録する
                </button>
            </div>

            <div class="auth-link-container">
                <a href="{{ route('login') }}" class="auth-link">ログインはこちら</a>
            </div>
        </form>
    </div>
@endsection