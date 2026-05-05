@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/products.css') }}">
@endsection

@section('content')
<div class="products">
    <div class="products__tabs">
        <a
            class="products__tab {{ $tab === 'recommend' ? 'products__tab--active' : '' }}"
            href="{{ route('products.index', ['tab' => 'recommend', 'keyword' => request('keyword')]) }}"
        >
            おすすめ
        </a>

        <a
            class="products__tab {{ $tab === 'mylist' ? 'products__tab--active' : '' }}"
            href="{{ route('products.index', ['tab' => 'mylist', 'keyword' => request('keyword')]) }}"
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
            {{--マイリスト未ログイン時や、ログイン後もお気に入りを押していない場合は、マイリストの画面は空白。文字を入れたい場合「表示する商品はありません」等の場合はここに入れる--}}
        @endforelse
    </div>
</div>
@endsection