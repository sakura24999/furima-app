@extends('layouts.app')

@section('title', 'プロフィール編集')
@section('styles')
    <link rel="stylesheet" href="{{asset('css/profile-edit.css')}}">
@endsection

@section('content')
    <div class="edit-container">
        <h2 class="page-title">プロフィール設定</h2>

        <form action="{{route('profile.update')}}" method="POST" enctype="multipart/form-data" class="profile-form">
            @csrf
            @method('PUT')

            <div class="form-section">
                <div class="avatar-section">
                    <div class="current-avatar">
                        @if (Auth::user()->avatar_path)
                            <img src="{{asset('storage/' . Auth::user()->avatar_path)}}" alt="{{Auth::user()->name}}"
                                class="avatar-image">
                        @else
                            <div class="avatar-placeholder"></div>
                        @endif
                    </div>
                    <div class="avatar-upload">
                        <label for="avatar" class="upload-button">画像を選択する</label>
                        <input type="file" name="avatar" id="avatar" class="avatar-input">
                    </div>
                    <div class="avatar-preview" id="avatar-preview"></div>
                </div>
                @error('avatar')
                    <p class="error-message">{{$message}}</p>
                @enderror
            </div>

            <div class="form-section">
                <div class="form-group">
                    <label for="name" class="form-label">ユーザー名</label>
                    <input type="text" name="name" id="name" class="form-input" value="{{old('name', Auth::user()->name)}}"
                        required>
                    @error('name')
                        <p class="error-message">{{$message}}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">メールアドレス</label>
                    <input type="email" name="email" id="email" class="form-input"
                        value="{{old('email', Auth::user()->email)}}" required>
                    @error('email')
                        <p class="error-message">{{$message}}</p>
                    @enderror
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">配送先情報</h3>

                <div class="form-group">
                    <label for="postal_code" class="form-label">郵便番号</label>
                    <input type="text" name="postal_code" id="postal_code" class="form-input"
                        value="{{old('postal_code', Auth::user()->postal_code)}}" placeholder="例: 123-4567">
                    @error('postal_code')
                        <p class="error-message">{{$message}}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="address" class="form-label">住所</label>
                    <input type="text" name="address" id="address" class="form-label"
                        value="{{old('address', Auth::user()->address)}}">
                    @error('address')
                        <p class="error-message">{{$message}}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="building" class="form-label">建物名</label>
                    <input type="text" name="building" id="building" class="form-input"
                        value="{{old('building', Auth::user()->building)}}" placeholder="任意">
                    @error('building')
                        <p class="error-message">{{$message}}</p>
                    @enderror
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">パスワード変更（変更する場合のみ入力）</h3>

                <div class="form-group">
                    <label for="current_password" class="form-label">現在のパスワード</label>
                    <input type="password" name="current_password" id="current_password" class="form-input">
                    @error('current_password')
                        <p class="error-message">{{$message}}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">新しいパスワード</label>
                    <input type="password" name="password" id="password" class="form-input">
                    @error('password')
                        <p class="error-message">{{$message}}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">パスワード確認</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-input">
                    @error('password_confirmation')
                        <p class="error-message">{{$message}}</p>
                    @enderror
                </div>
            </div>

            <div class="form-buttobs">
                <button class="update-button" type="submit">更新する</button>
                <a href="{{route('profile.show', Auth::user())}}" class="cancel-button">キャンセル</a>
            </div>
        </form>
    </div>
@endsection