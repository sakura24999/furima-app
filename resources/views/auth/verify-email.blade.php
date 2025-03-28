@extends('layouts.app')

@section('title', 'メール認証')
@section('styles')
    <link rel="stylesheet" href="{{asset('css/verify-email.css')}}">
@endsection

@section('content')
    <div class="verify-container">
        <div class="verify-message">
            <p class="message-text">登録していただいたメールアドレスに認証メールを送付しました。<br>
                メール認証を完了してください。</p>
        </div>

        <div class="verify-actions">
            <a href="{{route('verification.notice')}}" class="verify-button">認証はこちら</a>
        </div>

        <div class="resend-container">
            <form action="{{route('verification.send')}}" class="resend-form" method="POST">
                @csrf
                <button class="resend-link" type="submit">認証メールを再送する</button>
            </form>
        </div>
    </div>
@endsection
