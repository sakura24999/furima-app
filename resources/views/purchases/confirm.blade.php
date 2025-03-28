@extends('layouts.app')

@section('title', '購入確認')
@section('styles')
    <link rel="stylesheet" href="{{asset('css/purchase-confirm.css')}}">
@endsection

@section('content')
    <div class="purchase-container">
        <div class="purchase-header">
            <h2 class="page-title">購入確認</h2>
        </div>

        <div class="purchase-content">
            <div class="purchase-item-details">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">商品情報</h3>
                    </div>
                    <div class="card-body">
                        <div class="item-info">
                            <h4 class="item-name">{{$item->name}}</h4>
                            <p class="item-price">¥{{number_format($item->price)}}</p>
                            <p class="item-seller">出品者: {{$item->user->name}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="purchase-form-section">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">配送情報・支払い方法</h3>
                </div>
                <div class="card-body">
                    <form action="{{route('purchases.store', $item)}}" method="POST" class="purchase-form">
                        @csrf

                        <div class="shipping-address">
                            <h4 class="section-subtitle">配送先情報</h4>

                            <div class="form-group">
                                <label for="shipping_name" class="form-label">お名前</label>
                                <input type="text" name="shipping_name" id="shipping_name" class="form-control @error('shipping_name') is_invalid
                                @enderror" value="{{old('shipping_name', $user->name)}}" required>
                                @error('shipping_name')
                                    <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="shipping_postal_code" class="form-label">郵便番号（ハイフンなし）</label>
                                <input type="text" name="shipping_postal_code" id="shipping_postal_code" class="form-control @error('shipping_postal_code') is_invalid
                                @enderror" value="{{old('shipping_postal_code', $user->postal_code)}}"
                                    placeholder="1234567" pattern="[0-9]{7}" required>
                                @error('shipping_postal_code')
                                    <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="shipping_address" class="form-label">住所</label>
                                <input type="text" name="shipping_address" id="shipping_address" class="form-control @error('shipping_address')is-invalid
                                @enderror" value="{{old('shipping_address', $user->address)}}" required>
                                @error('shipping_address')
                                    <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="shipping_phone" class="form-label">電話番号</label>
                                <input type="tel" name="shipping_phone" id="shipping_phone" class="form-control @error('shipping_phone')is-invalid
                                @enderror" value="{{old('shipping_phone', '$user->phone')}}" required>
                                @error('shipping_phone')
                                    <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="payment-method">
                            <h4 class="section-subtitle">支払い方法</h4>

                            <div class="form-group">
                                <div class="payment-options">
                                    <div class="payment-option">
                                        <input type="radio" name="payment_method" id="credit_card" value="credit_card"
                                            class="payment-radio" {{old('payment_method') === 'credit_card' ? 'checked' : 'checked'}}>
                                        <label for="credit_card" class="payment-label">クレジットカード</label>
                                    </div>

                                    <div class="payment-option">
                                        <input type="radio" name="payment_method" id="bank_transfer" value="bank_transfer"
                                            class="payment-radio" {{old('payment_method') === 'bank_transfer' ? 'cheked' : ''}}>
                                        <label for="bank_transfer" class="payment-label">銀行振込</label>
                                    </div>

                                    <div class="payment-option">
                                        <input type="radio" name="payment_method" id="convenience_store"
                                            value="convenience_store" class="payment-radio"
                                            {{old('payment_method') === 'convenience_store' ? 'checked' : ''}}>
                                        <label for="convenience_store" class="payment-label">コンビニ払い</label>
                                    </div>
                                </div>
                                @error('payment_method')
                                    <div class="invalid-feedback d-block">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="purchase-summary">
                            <h4 class="section-subtitle">購入内容の確認</h4>

                            <div class="summary-row">
                                <span class="summary-label">商品全額</span>
                                <span class="summary-value">¥{{number_format($item->price)}}</span>
                            </div>

                            <div class="summary-row">
                                <span class="summary-label">配送料</span>
                                <span class="summary-value">無料</span>
                            </div>

                            <div class="summary-row total">
                                <span class="summary-label">合計金額</span>
                                <span class="summary-value">¥{{number_format($item->price)}}</span>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button class="btn btn-primary btn-purchase" type="submit">購入を確定する</button>
                            <a href="{{route('items.show', $item)}}" class="btn btn-secondary">キャンセル</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
