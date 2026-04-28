@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<form class="purchase" action="{{ route('products.purchase.store', $product->id) }}" method="POST">
    @csrf
    <div class="purchase__main">
        <div class="purchase__product">
            <div class="purchase__image-wrap">
                @if ($product->image)
                    <img
                        class="purchase__image"
                        src="{{ asset('storage/' . $product->image) }}"
                        alt="{{ $product->name }}"
                    >
                @else
                    <div class="purchase__image-placeholder">
                        商品画像
                    </div>
                @endif
            </div>

            <div class="purchase__product-info">
                <h1 class="purchase__product-name">
                    {{ $product->name }}
                </h1>

                <p class="purchase__product-price">
                    ¥{{ number_format($product->price) }}
                </p>
            </div>
        </div>

        <div class="purchase__section">
            <h2 class="purchase__section-title">支払い方法</h2>

            <select class="purchase__select" name="payment" id="paymentSelect">
                <option value="" disabled {{ old('payment') ? '' : 'selected' }} hidden>
                    選択してください
                </option>
                <option value="コンビニ払い" {{ old('payment') === 'コンビニ払い' ? 'selected' : '' }}>
                    コンビニ払い
                </option>
                <option value="カード払い" {{ old('payment') === 'カード払い' ? 'selected' : '' }}>
                    カード払い
                </option>
            </select>

            @foreach ($errors->get('payment') as $message)
                <p class="purchase__error">{{ $message }}</p>
            @endforeach
        </div>

        <div class="purchase__section">
            <div class="purchase__section-header">
                <h2 class="purchase__section-title">配送先</h2>

                <a
                    class="purchase__address-link"
                    href="{{ route('purchase.address.edit', $product->id) }}"
                >
                    変更する
                </a>
            </div>

            <div class="purchase__address">
               @if ($address)
                    <p class="purchase__address-text">
                        〒 {{ $address['zip_code'] }}
                    </p>
                    <p class="purchase__address-text">
                        {{ $address['residence'] }} {{ $address['building'] }}
                    </p>
                @else
                    <p class="purchase__address-text">
                        配送先が未設定です
                    </p>
                @endif
            </div>
        </div>
    </div>

    <div class="purchase__side">
        <div class="purchase__summary">
            <div class="purchase__summary-row">
                <span class="purchase__summary-label">商品代金</span>
                <span class="purchase__summary-value">
                    ¥{{ number_format($product->price) }}
                </span>
            </div>

            <div class="purchase__summary-row">
                <span class="purchase__summary-label">支払い方法</span>
                <span class="purchase__summary-value" id="paymentSummary">
                    選択してください
                </span>
            </div>
            </div>

            <button class="purchase__button" type="submit">
                購入する
            </button>
        </div>
    </form>

    <script>
        const paymentSelect = document.getElementById('paymentSelect');
        const paymentSummary = document.getElementById('paymentSummary');

        paymentSelect.addEventListener('change', function () {
            paymentSummary.textContent = paymentSelect.value || '選択してください';
        });
    </script>
@endsection