@extends('layouts.app')

@section('title', 'ログイン')

@section('styles')
    <link rel="stylesheet" href="{{asset('css/auth.css')}}">
@endsection

@section('content')
    <div class="auth-container">
        <h2 class="auth-title">ログイン</h2>

        <form action="{{route('login')}}" class="auth-form" method="POST">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">メールアドレス</label>
                <input type="email" id="email" class="form-input @error('email') is-invalid
                @enderror" name="email" value="{{old('email')}}" required autocomplete="email" autofocus>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{$message}}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">パスワード</label>
                <input type="password" id="password" class="form-input @error('password') is-invalid
                @enderror" name="password" required autocomplete="current-password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{$message}}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">パスワード</label>
                <input type="password" type="password" class="form-input @error('password') is-invalid
                @enderror" name="password" required autocomplete="current-password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{$message}}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-button">
                <button class="auth-button" type="submit">
                    ログインする
                </button>
            </div>

            <div class="auth-link-container">
                <a href="{{route('register')}}" class="auth-link">会員登録はこちら</a>
            </div>
        </form>
    </div>
@endsection