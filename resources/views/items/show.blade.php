@extends('layouts.app')

@section('title', $item->name)

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/items-show.css') }}">
@endsection

@section('content')
<div class="item-detail-container">
    <div class="item-detail-header">
        <h2 class="item-detail-title">{{ $item->name }}</h2>
    </div>

    <div class="item-detail-content">
        <div class="item-detail-left">
            <div class="item-detail-image">
                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="detail-image">
            </div>

            @if(count($item->categories) > 0)
                <div class="item-detail-categories">
                    @foreach($item->categories as $category)
                        <span class="detail-category-tag">{{ $category->name }}</span>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="item-detail-right">
            <div class="item-detail-info">
                <div class="item-detail-price">
                    <span class="price-label">価格</span>
                    <span class="price-value">¥{{ number_format($item->price) }}</span>
                </div>

                <div class="item-detail-condition">
                    <span class="condition-label">商品の状態</span>
                    <span class="condition-value">
                        @switch($item->condition)
                            @case('new')
                                新品・未使用
                                @break
                            @case('like_new')
                                未使用に近い
                                @break
                            @case('good')
                                目立った傷や汚れなし
                                @break
                            @case('fair')
                                やや傷や汚れあり
                                @break
                            @case('poor')
                                傷や汚れあり
                                @break
                            @default
                                不明
                        @endswitch
                    </span>
                </div>

                <div class="item-detail-seller">
                    <span class="seller-label">出品者</span>
                    <a href="{{ route('profile.show', $item->user) }}" class="seller-link">{{ $item->user->name }}</a>
                </div>

                <div class="item-detail-actions">
                    @auth
                        @if($item->user_id !== Auth::id())
                            @if($item->status === 'available')
                                <a href="{{ route('purchases.confirm', $item) }}" class="btn btn-primary btn-block action-buy">購入する</a>
                            @else
                                <button class="btn btn-secondary btn-block" disabled>売り切れ</button>
                            @endif

                            <button class="btn like-button {{ $isLiked ? 'liked' : '' }}"
                                    data-item-id="{{ $item->id }}"
                                    data-liked="{{ $isLiked ? 'true' : 'false' }}">
                                @if($isLiked)
                                    &#9829; いいね中
                                @else
                                    &#9825; いいね
                                @endif
                            </button>
                            <span class="like-count" data-item-id="{{ $item->id }}">{{ $likesCount }}</span>
                        @else
                            <div class="own-item-actions">
                                <a href="{{ route('items.edit', $item) }}" class="btn btn-secondary">編集する</a>
                                <form action="{{ route('items.destroy', $item) }}" method="POST" class="delete-form" onsubmit="return confirm('本当に削除しますか？');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">削除する</button>
                                </form>
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-block">ログインして購入</a>
                    @endauth
                </div>
            </div>

            <div class="item-detail-description">
                <h3 class="description-title">商品説明</h3>
                <div class="description-content">
                    {!! nl2br(e($item->description)) !!}
                </div>
            </div>
        </div>
    </div>

    <!-- コメントセクション -->
    <div class="item-comments-section">
        <h3 class="comments-title">コメント一覧</h3>

        <div class="comments-list">
            @forelse($item->comments as $comment)
                <div class="comment-item">
                    <div class="comment-header">
                        <a href="{{ route('profile.show', $comment->user) }}" class="comment-user">{{ $comment->user->name }}</a>
                        <span class="comment-date">{{ $comment->created_at->format('Y/m/d H:i') }}</span>

                        @can('delete', $comment)
                            <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="comment-delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="comment-delete-btn">×</button>
                            </form>
                        @endcan
                    </div>
                    <div class="comment-body">
                        {!! nl2br(e($comment->body)) !!}
                    </div>
                </div>
            @empty
                <div class="no-comments">
                    <p>コメントはまだありません。</p>
                </div>
            @endforelse
        </div>

        @auth
            <div class="comment-form-container">
                <form action="{{ route('comments.store', $item) }}" method="POST" class="comment-form" data-item-id="{{ $item->id }}">
                    @csrf
                    <textarea name="body" class="form-control comment-input" placeholder="コメントを入力してください" required></textarea>
                    <button type="submit" class="btn btn-primary comment-submit">コメントする</button>
                </form>
            </div>
        @else
            <div class="login-to-comment">
                <a href="{{ route('login') }}" class="login-link">ログインしてコメントする</a>
            </div>
        @endauth
    </div>

    <!-- 関連商品 -->
    @if(count($item->categories) > 0)
        <div class="related-items-section">
            <h3 class="related-title">関連商品</h3>

            <div class="related-items-grid">
                @foreach($item->categories[0]->items()->where('id', '!=', $item->id)->where('status', 'available')->latest()->take(4)->get() as $relatedItem)
                    <div class="item-card">
                        <a href="{{ route('items.show', $relatedItem) }}" class="item-link">
                            <div class="item-image">
                                <img src="{{ asset('storage/' . $relatedItem->image) }}" alt="{{ $relatedItem->name }}" class="item-thumbnail">
                            </div>
                            <div class="item-details">
                                <h3 class="item-name">{{ $relatedItem->name }}</h3>
                                <p class="item-price">¥{{ number_format($relatedItem->price) }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
