@extends('layouts.app')

@section('title', '商品出品')
@section('styles')
<link rel="stylesheet" href="{{asset('css/items-create.css')}}">

@endsection
@section('content')
    <div class="create-item-container">
        <div class="create-item-header">
            <h2 class="page-title">商品を出品する</h2>
        </div>

        <div class="create-item-content">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('items.store')}}" method="POST" class="item-form" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="name" class="form-label required">商品名</label>
                        <input type="text" name="name" id="name" class="form-control @error('name')
                        is-invalid

                        @enderror"
                        value="{{old('name')}}" required>
                        @error('name')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label required">商品説明</label>
                        <textarea name="description" id="description" rows="5" class="form-control @error('description') is-invalid
                        @enderror" required>{{old('description')}}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="categories" class="form-label required">カテゴリー</label>
                        <div class="categories-select">
                            @foreach ($categories as $category)
                            <div class="category-checkbox">
                                <input type="checkbox" name="categories[]" id="category-{{$category->id}}" value="{{$category->id}}" class="category-input"
                                {{in_array($category->id, old('categories', [])) ? 'checked' : ''}}>
                                <label for="category-{{$category->id}}" class="category-label">{{$category->name}}</label>
                            </div>

                            @endforeach
                        </div>
                        @error('categories')
                        <div class="invalid-feedback d-block">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="condition" class="form-label required">商品の状態</label>
                        <select name="condition" id="condition" class="form-control @error('condition') is-invalid
                        @enderror" required>
                        <option value="" disabled {{old('condition') ? '' : 'selected'}}>選択してください</option>
                        <option value="new" {{old('condition') === 'new' ? 'selected' : ''}}>新品・未使用</option>
                        <option value="like_new" {{old('condition') === 'like_new' ? 'selected' : ''}}>未使用に近い</option>
                        <option value="good" {{old('condition') === 'good' ? 'selected' : ''}}>目立った傷や汚れなし</option>
                        <option value="fair" {{old('condition') === 'fair' ? 'selected' : ''}}>やや傷や汚れあり</option>
                        <option value="poor" {{old('condition') === 'poor' ? 'selected' : ''}}>傷や汚れあり</option>
                    </select>
                    @error('condition')
                    <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                    </div>

                    <div class="form-group">
                        <label for="price" class="form-label required">価格（円）</label>
                        <input type="price" name="price" id="price" class="form-control price-input @error('price') is-invalid
                        @enderror" value="{{old('price')}}" placeholder="例:1000" required>
                        @error('price')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="image" class="form-label required">商品画像</label>
                        <div class="image-upload-container">
                            <input type="file" name="image" id="image" class="image-upload-input @error('image') is-invalid @enderror"
                                accept="image/*" required>
                                <div class="image-preview" style="display: none;"></div>
                        </div>
                        <small class="form-text text-muted">JPG, PNG, GIF形式の画像をアップロードしてください（最大2MB）</small>
                        @error('image')
                        <div class="invalid-feedback d-block">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <button class="submit" class="btn btn-primary">出品する</button>
                        <a href="{{route('items.index')}}" class="btn btn-secondary">キャンセル</a>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>

@endsection
