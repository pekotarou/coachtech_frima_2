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
            {{ $product->brand_name }}
        </p>

        <p class="product-detail__price">
            <span class="product-detail__price-mark">¥</span>{{ number_format($product->price) }} <span class="product-detail__tax">(税込)</span>
        </p>

        <div class="product-detail__actions">
            <div class="product-detail__action">
                @auth
                    {{--ログイン済みならハートを押せる --}}
                    <form class="product-detail__heart-form" action="{{ route('products.heart', $product->id) }}" method="POST">
                        @csrf

                        <button class="product-detail__heart-button" type="submit">
                            @if ($isHearted)
                                <img
                                    class="product-detail__heart-icon"
                                    src="{{ asset('images/icons/heart-active.png') }}"
                                    alt="いいね済み"
                                >
                            @else
                                <img
                                    class="product-detail__heart-icon"
                                    src="{{ asset('images/icons/heart-default.png') }}"
                                    alt="いいね"
                                >
                            @endif
                        </button>
                    </form>
                @else
                    {{--未ログインなら押せない表示だけ --}}
                    <img
                        class="product-detail__heart-icon"
                        src="{{ asset('images/icons/heart-default.png') }}"
                        alt="いいね"
                    >
                @endauth

                <span class="product-detail__count">
                    {{ $heartCount }}
                </span>
            </div>

            <div class="product-detail__action">
                <img
                    class="product-detail__comment-count-icon"
                    src="{{ asset('images/icons/comment.png') }}"
                    alt="コメント"
                >

                <span class="product-detail__count">
                    {{ $commentCount }}
                </span>
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
            <h2 class="product-detail__section-title product-detail__section-title--comment">
                コメント({{ $commentCount }})
            </h2>

            {{--コメント一覧--}}
            <div class="product-detail__comments">
                @forelse ($product->comments as $comment)
                    <div class="product-detail__comment-item">
                        <div class="product-detail__comment-user">
                            <div class="product-detail__comment-icon">
                                @if ($comment->user->profile && $comment->user->profile->image)
                                    <img
                                        class="product-detail__comment-user-image"
                                        src="{{ asset('storage/' . $comment->user->profile->image) }}"
                                        alt="コメント投稿者の画像"
                                    >
                                @endif
                            </div>
                            <span class="product-detail__comment-name">
                                {{ $comment->user->profile->name ?? $comment->user->name ?? 'ユーザー' }}
                            </span>
                        </div>
                        <div class="product-detail__comment-box">
                            {{ $comment->comment }}
                        </div>
                    </div>
                @empty
                    <p class="product-detail__comment-empty">
                        コメントはまだありません。
                    </p>
                @endforelse
            </div>



            <form class="product-detail__comment-form" action="{{ route('products.comment', $product->id) }}" method="POST">
                @csrf

                <label class="product-detail__comment-label" for="comment">
                    商品へのコメント
                </label>

                <textarea
                    class="product-detail__comment-textarea"
                    id="comment"
                    name="comment"
                >{{ old('comment') }}</textarea>

                @foreach ($errors->get('comment') as $message)
                    <p class="product-detail__error">{{ $message }}</p>
                @endforeach

                <button class="product-detail__comment-button" type="submit">
                    コメントを送信する
                </button>
            </form>




        </section>
    </div>
</div>
@endsection