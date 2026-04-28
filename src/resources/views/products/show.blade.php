@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/product-detail.css') }}">
@endsection

@section('content')

{{-- 購入できない場合などのエラーメッセージ --}}
@if (session('error'))
    <div class="product-detail__flash-error">
        {{ session('error') }}
    </div>
@endif



<div class="product-detail">
    <div class="product-detail__image-area">
        @if ($product->image)
            <img
                class="product-detail__image"
                src="{{ asset('storage/' . $product->image) }}"
                alt="{{ $product->name }}"
            >
        @else
            <div class="product-detail__image-placeholder">
                商品画像
            </div>
        @endif
    </div>

    <div class="product-detail__content">
        <h1 class="product-detail__name">
            {{ $product->name }}
        </h1>

        <p class="product-detail__brand">
            {{ $product->brand->name ?? 'ブランドなし' }}
        </p>

        <p class="product-detail__price">
            ¥{{ number_format($product->price) }} <span class="product-detail__tax">(税込)</span>
        </p>

        <div class="product-detail__actions">
            <div class="product-detail__action">
                <span class="product-detail__icon">♡</span>
                <span class="product-detail__count">0</span>
            </div>

            <div class="product-detail__action">
                <span class="product-detail__icon">💬</span>
                <span class="product-detail__count">0</span>
            </div>
        </div>

        <a class="product-detail__purchase-button" href="{{ route('products.purchase', $product->id) }}">
            購入手続きへ
        </a>

        <section class="product-detail__section">
            <h2 class="product-detail__section-title">商品説明</h2>
            <p class="product-detail__description">
                {{ $product->description }}
            </p>
        </section>

        <section class="product-detail__section">
            <h2 class="product-detail__section-title">商品の情報</h2>

            <dl class="product-detail__info">
                <div class="product-detail__info-row">
                    <dt class="product-detail__info-label">カテゴリー</dt>
                    <dd class="product-detail__info-value">
                        <div class="product-detail__categories">
                            @forelse ($product->categories as $category)
                                <span class="product-detail__category">
                                    {{ $category->content }}
                                </span>
                            @empty
                                <span class="product-detail__category">
                                    未設定
                                </span>
                            @endforelse
                        </div>
                    </dd>
                </div>

                <div class="product-detail__info-row">
                    <dt class="product-detail__info-label">商品の状態</dt>
                    <dd class="product-detail__info-value">
                        {{ $product->status->status ?? '未設定' }}
                    </dd>
                </div>
            </dl>
        </section>

        <section class="product-detail__section">
            <h2 class="product-detail__section-title">コメント</h2>

            <div class="product-detail__comment-user">
                <div class="product-detail__comment-icon"></div>
                <span class="product-detail__comment-name">
                    {{ $product->user->profile->name ?? $product->user->name ?? 'ユーザー' }}
                </span>
            </div>

            <div class="product-detail__comment-box">
                コメントはまだありません。
            </div>

            <label class="product-detail__comment-label" for="comment">
                商品へのコメント
            </label>

            <textarea class="product-detail__comment-textarea" id="comment" name="comment"></textarea>

            <button class="product-detail__comment-button" type="button">
                コメントを送信する
            </button>
        </section>
    </div>
</div>
@endsection