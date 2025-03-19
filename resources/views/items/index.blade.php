@extends('layouts.app')

@section('title', '商品一覧')

@section('styles')
    <link rel="stylesheet" href="{{asset('css/items-index.css')}}">

@endsection

@section('content')
    <div class="items-container">
        <div class="items-header">
            <h2 class="page-title">商品一覧</h2>

            <div class="filter-container">
                <form action="{{route('items.index')}}" method="GET" class="filter-form">
                    <div class="form-group">
                        <label for="category" class="filter-label">カテゴリー</label>
                        <select name="category" id="category" class="form-control">
                            <option value="">すべてのカテゴリー</option>
                            @foreach ($categories as $cat)
                                <option value="{{$cat->id}}" {{request('category') == $cat->id ? 'selected' : ''}}>
                                    {{$cat->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button class="btn btn-primary filter-button">絞り込む</button>
                </form>
            </div>
        </div>

        @if (count($items) > 0)
            <div class="items-grid">
                @foreach ($items as $item)
                    <div class="item-card">
                        <a href="{{route('items.show', $item)}}" class="item-link">
                            <div class="item-image">
                                <img src="{{asset('storage/' . $item->image)}}" alt="{{$item->name}}" class="item-thumbnail">
                            </div>
                            <div class="item-details">
                                <h3 class="item-name">{{$item->name}}</h3>
                                <p class="item-price">¥{{number_format($item->price)}}</p>

                                @if (count($item->categories) > 0)
                                    <div class="item-categories">
                                        @foreach ($item->categories as $category)
                                            <span class="category-tag">{{$category->name}}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </a>
                    </div>

                @endforeach
            </div>

            <div class="pagination-container">
                {{$items->appends(request()->query())->links()}}
            </div>
        @else
            <div class="no-items">
                <p>商品が見つかりませんでした。</p>
                @if (request('search') || request('category'))
                    <p>検索条件を変更して再度お試しください。</p>
                    <a href="{{route('items.index')}}" class="btn btn-secondary">全ての商品を表示</a>
                @endif
            </div>
        @endif
    </div>

@endsection