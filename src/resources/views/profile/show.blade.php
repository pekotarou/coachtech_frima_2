@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
<link rel="stylesheet" href="{{ asset('css/products.css') }}">
@endsection

@section('content')
<div class="profile-show">
    <div class="profile-show__header">
        <div class="profile-show__image-wrap">
            @if ($user->profile && $user->profile->image)
                <img
                    class="profile-show__image"
                    src="{{ asset('storage/' . $user->profile->image) }}"
                    alt="プロフィール画像"
                >
            @else
                <div class="profile-show__image-placeholder"></div>
            @endif
        </div>

        <div class="profile-show__info">
            <h1 class="profile-show__name">
                {{ $user->profile->name ?? 'ユーザー名未設定' }}
            </h1>

            <a class="profile-show__edit" href="{{ route('profile.edit') }}">
                プロフィールを編集
            </a>
        </div>
    </div>

    {{-- 修正: マイページ用タブ --}}
    <div class="profile-show__tabs">
        <a
            class="profile-show__tab {{ $page === 'sell' ? 'profile-show__tab--active' : '' }}"
            href="{{ route('profile.show', ['page' => 'sell']) }}"
        >
            出品した商品
        </a>

        <a
            class="profile-show__tab {{ $page === 'buy' ? 'profile-show__tab--active' : '' }}"
            href="{{ route('profile.show', ['page' => 'buy']) }}"
        >
            購入した商品
        </a>
    </div>

    {{-- 修正: 商品一覧 --}}
    <div class="products__list profile-show__products">
        @forelse ($products as $product)
            <a class="products__card" href="{{ route('products.show', $product->id) }}">
                <div class="products__image-wrap">
                    @if ($product->image)
                        <img
                            class="products__image"
                            src="{{ asset('storage/' . $product->image) }}"
                            alt="{{ $product->name }}"
                        >
                    @else
                        <div class="products__image-placeholder">
                            商品画像
                        </div>
                    @endif

                    @if ($product->order_id)
                        <span class="products__sold">Sold</span>
                    @endif
                </div>

                <p class="products__name">
                    {{ $product->name }}
                </p>
            </a>
        @empty
            <p class="products__empty">
                表示する商品がありません
            </p>
        @endforelse
    </div>
</div>
@endsection