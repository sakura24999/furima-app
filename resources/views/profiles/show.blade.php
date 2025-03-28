@extends('layouts.app')

@section('title', $user->name . 'のプロフィール')
@section('styles')
    <link rel="stylesheet" href="{{asset('css/profile-show.css')}}">

@endsection
@section('content')
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-cover"></div>
            <div class="profile-info">
                <div class="profile-avatar">
                    @if ('$user->avatar')
                        <img src="{{asset('storage/' . $user->avatar)}}" alt="{{$user->name}}" class="avatar-image">
                    @else
                        <img src="{{asset('storage/avatars/default.png')}}" alt="{{$user->name}}" class="avatar-image">
                    @endif
                </div>
                <div class="profile-details">
                    <h2 class="profile-name">{{$user->name}}</h2>
                    <p class="profile-date">登録日: {{$user->created_at->format('Y年m月d日')}}</p>

                    @if ($user->bio)
                        <div class="profile-bio">
                            {!! nl2br(e($user->bio)) !!}
                        </div>

                    @endif

                    @if (Auth::id() === $user->id)
                        <div class="profile-actions">
                            <a href="{{route('profile.edit')}}" class="btn btn-secondary">プロフィール編集</a>
                        </div>

                    @endif
                </div>
            </div>
        </div>

        <div class="profile-content">
            <div class="profile-section">
                <div class="section-header">
                    <h3 class="section-title">出品した商品</h3>
                    @if (count($items) > 0 && Auth::id() === $user->id)
                        <a href="{{route('items.myItems')}}" class="section-link">すべて見る</a>
                    @endif
                </div>

                @if (count($items) > 0)
                    <div class="items-grid">
                        @foreach ($items as $item)
                            <div class="item-card">
                                <a href="{{route('items.show', $item)}}" class="item-link">
                                    <div class="item-image">
                                        <img src="{{asset('storage/' . $item->image)}}" alt="{{$item->name}}"
                                            class="item-thumbnail">
                                        @if ($item->status !== 'available')
                                            <div class="item-status">SOLD</div>
                                        @endif
                                    </div>
                                    <div class="item-details">
                                        <h3 class="item-name">{{$item->name}}</h3>
                                        <p class="item-price">¥{{number_format($item->price)}}</p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="no-items">
                        <p>出品した商品はありません</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection