@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="sell">
    <h1 class="sell__title">商品の出品</h1>

    <form class="sell__form" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- 商品画像 --}}
        <div class="sell__group">
            <label class="sell__label">商品画像</label>

            <div class="sell__image-area">
                <img id="preview" class="sell__image-preview" src="" alt="商品画像プレビュー">

                <label class="sell__image-button" for="imageInput">
                    画像を選択する
                </label>

                <input
                    class="sell__image-input"
                    type="file"
                    name="image"
                    id="imageInput"
                >
            </div>

            @foreach ($errors->get('image') as $message)
                <p class="sell__error">{{ $message }}</p>
            @endforeach
        </div>

        {{-- 商品の詳細 --}}
        <section class="sell__section">
            <h2 class="sell__section-title">商品の詳細</h2>

            {{-- カテゴリー --}}
            <div class="sell__group">
                <label class="sell__label">カテゴリー</label>

                <div class="sell__categories">
                    @foreach ($categories as $category)
                        <label class="sell__category-label">
                            <input
                                class="sell__category-input"
                                type="checkbox"
                                name="category_ids[]"
                                value="{{ $category->id }}"
                                {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}
                            >
                            <span class="sell__category-text">
                                {{ $category->content }}
                            </span>
                        </label>
                    @endforeach
                </div>

                @foreach ($errors->get('category_ids') as $message)
                    <p class="sell__error">{{ $message }}</p>
                @endforeach

                @foreach ($errors->get('category_ids.*') as $message)
                    <p class="sell__error">{{ $message }}</p>
                @endforeach
            </div>

            {{-- 商品の状態 --}}
            <div class="sell__group">
                <label class="sell__label" for="status_id">商品の状態</label>

                <select class="sell__select" name="status_id" id="status_id">
                    <option value="" hidden>選択してください</option>
                    @foreach ($statuses as $status)
                        <option
                            value="{{ $status->id }}"
                            {{ old('status_id') == $status->id ? 'selected' : '' }}
                        >
                            {{ $status->status }}
                        </option>
                    @endforeach
                </select>

                @foreach ($errors->get('status_id') as $message)
                    <p class="sell__error">{{ $message }}</p>
                @endforeach
            </div>
        </section>

        {{-- 商品名と説明 --}}
        <section class="sell__section">
            <h2 class="sell__section-title">商品名と説明</h2>

            {{-- 商品名 --}}
            <div class="sell__group">
                <label class="sell__label" for="name">商品名</label>
                <input
                    class="sell__input"
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name') }}"
                >

                @foreach ($errors->get('name') as $message)
                    <p class="sell__error">{{ $message }}</p>
                @endforeach
            </div>

            {{-- ブランド名 --}}
            <div class="sell__group">
                <label class="sell__label" for="brand_name">ブランド名</label>
                <input
                    class="sell__input"
                    type="text"
                    name="brand_name"
                    id="brand_name"
                    value="{{ old('brand_name') }}"
                    placeholder="例：COACHTECH"
                >

                @foreach ($errors->get('brand_name') as $message)
                    <p class="sell__error">{{ $message }}</p>
                @endforeach
            </div>

            {{-- 商品説明 --}}
            <div class="sell__group">
                <label class="sell__label" for="description">商品の説明</label>
                <textarea
                    class="sell__textarea"
                    name="description"
                    id="description"
                >{{ old('description') }}</textarea>

                @foreach ($errors->get('description') as $message)
                    <p class="sell__error">{{ $message }}</p>
                @endforeach
            </div>
     

        {{-- 販売価格 --}}
       

            <div class="sell__group">
                <label class="sell__label" for="price">販売価格</label>

                <div class="sell__price-wrap">
                    <span class="sell__price-mark">¥</span>
                    <input
                        class="sell__input sell__input--price"
                        type="number"
                        name="price"
                        id="price"
                        value="{{ old('price') }}"
                    >
                </div>

                @foreach ($errors->get('price') as $message)
                    <p class="sell__error">{{ $message }}</p>
                @endforeach
            </div>
        </section>

        <button class="sell__submit" type="submit">
            出品する
        </button>
    </form>
</div>

<script>
    const imageInput = document.getElementById('imageInput');
    const preview = document.getElementById('preview');

    imageInput.addEventListener('change', function (event) {
        const file = event.target.files[0];

        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        }
    });
</script>
@endsection