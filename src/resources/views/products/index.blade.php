@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/products.css') }}">
@endsection

@section('content')
<div class="products">
    <div class="products__tabs">
        <a
            class="products__tab {{ $tab === 'recommend' ? 'products__tab--active' : '' }}"
            href="{{ route('products.index', ['tab' => 'recommend']) }}"
        >
            おすすめ
        </a>

        <a
            class="products__tab {{ $tab === 'mylist' ? 'products__tab--active' : '' }}"
            href="{{ route('products.index', ['tab' => 'mylist']) }}"
        >
            マイリスト
        </a>
    </div>

    <div class="products__list">
        @forelse ($products as $product)
            <a class="products__card" href="{{ route('products.show', $product->id) }}">
                <div class="products__image-wrap">
                    @if ($product->image)
                        {{-- 商品画像は storage から表示 --}}
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

                    {{-- 修正: order_id があれば購入済み表示 --}}
                    @if ($product->order_id)
                        <span class="products__sold">Sold</span>
                    @endif
                </div>

                <p class="products__name">
                    {{ $product->name }}
                </p>
            </a>
        @empty
            {{-- 修正: マイリスト未ログイン時など --}}
            <p class="products__empty">
                表示する商品がありません
            </p>
        @endforelse
    </div>
</div>
@endsection