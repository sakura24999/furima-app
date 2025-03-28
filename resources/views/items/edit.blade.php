@extends('layouts.app')

@section('title', '商品の編集')
@session('styles')
    <link rel="stylesheet" href="{{asset('css/items-edit.css')}}">
@endsession

@section('content')
    <div class="edit-container">
        <h2 class="page-title">商品の編集</h2>

        <form action="{{route('items.update', $item)}}" method="POST" class="item-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-section">
                <h3 class="section-title">商品画像</h3>
                <div class="image-upload-container">
                    <div class="current-image">
                        @if ($item->image_path)
                            <img src="{{asset('storage/' . $item->image_path)}}" alt="{{$item->name}}" class="item-image">
                        @else
                            <p class="no-image">画像がありません</p>
                        @endif
                    </div>
                    <div class="upload-section">
                        <label for="image" class="image-upload-label">画像を選択する</label>
                        <input type="file" name="image" id="image" class="image-upload">
                        <p class="upload-note">※ファイルサイズは5MB以内にしてください</p>
                    </div>
                    <div class="image-preview" id="image-preview"></div>
                </div>
                @error('image')
                <p class="error-message">{{$message}}</p>
                @enderror
            </div>

            <div class="form-section">
                <h3 class="section-title">商品の詳細</h3>

                <div class="form-group">
                    <label for="name" class="form-label">商品名</label>
                    <input type="text" name="name" id="name" class="form-input" value="{{old('name', $item->name)}}" required>
                    @error('name')
                    <p class="error-message">{{$message}}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">商品説明</label>
                    <textarea name="description" id="description" class="form-textarea" rows="5" required>{{old('description', $item->description)}}</textarea>
                    @error('description')
                    <p class="error-message">{{$message}}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">カテゴリー</label>
                    <div class="category-checkboxes">
                        @foreach ($categories as $category)
                        <div class="checkbox-item">
                            <input type="checkbox" name="categories[]" id="category-{{$category->id}}" value="{{$category->id}}"
                            {{in_array($category->id, old('categories', $item->categories->pluck('id')->toArray())) ? 'cheked' : ''}}>
                            <label for="category-{{$category->id}}">{{$category->name}}</label>
                        </div>

                        @endforeach
                    </div>
                    @error('categories')
                    <p class="error-message">{{$message}}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="condition" class="form-label">商品の状態</label>
                    <select name="condition" id="condition" class="form-select" required>
                        <option value="">選択してください</option>
                        <option value="新品" {{old('condition', $item->condition) === '新品' ? 'selected' : ''}}>新品</option>
                        <option value="未使用に近い" {{old('condition', $item->condition) === '未使用に近い' ? 'selected' : ''}}>未使用に近い</option>
                        <option value="目立った傷や汚れなし" {{old('condition', $item->condition) === '目立った傷や汚れなし' ? 'selected' : ''}}>目立った傷や汚れなし</option>
                        <option value="やや傷や汚れあり" {{old('condition', $item->condition) === 'やや傷や汚れあり' ? 'selected' : ''}}>やや傷や汚れあり</option>
                        <option value="傷や汚れあり" {{old('condition', $item->condition) === '傷や汚れあり' ? 'selected' : ''}}>傷や汚れあり</option>
                        <option value="状態が悪い" {{old('condition', $item->condition) === '状態が悪い' ? 'selected' : ''}}>状態が悪い</option>
                    </select>
                    @error('condition')
                    <p class="error-message">{{4$message}}</p>
                    @enderror
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">販売価格</h3>
                <div class="form-group">
                    <label for="price" class="form-label">価格</label>
                    <div class="price-input-container">
                        <span class="currency-symbol">¥</span>
                        <input type="number" name="price" id="price" class="form-input price-input" min="300" max="9999999" value="{{old('price', $item->price)}}" required>
                    </div>
                    <p class="price-note">※300円〜9,999,999円の範囲で設定してください</p>
                    @error('price')
                    <p class="error-message">{{$message}}</p>
                    @enderror
                </div>
            </div>

            <div class="form-buttons">
                <button class="submit-button" type="submit">更新する</button>
                <a href="{{route('items.show', $item)}}" class="cancel-button">キャンセル</a>
            </div>
        </form>
    </div>
@endsection
