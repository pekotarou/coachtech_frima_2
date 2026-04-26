@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-create">
    <h1 class="profile-create__title">プロフィール設定</h1>

    <form class="profile-create__form" action="{{ route('profile.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- プロフィール画像 --}}
        <div class="profile-create__image-area">
            <div class="profile-create__image-preview">
                <img id="preview" class="profile-create__image" src="" alt="プロフィール画像プレビュー">
            </div>

            <label class="profile-create__image-button" for="imageInput">
                画像を選択する
            </label>

            <input class="profile-create__image-input" type="file" name="image" id="imageInput">

            @foreach ($errors->get('image') as $message)
                <p class="profile-create__error">{{ $message }}</p>
            @endforeach
        </div>

        {{-- ニックネーム --}}
        <div class="profile-create__group">
            <label class="profile-create__label" for="name">ユーザー名</label>
            <input class="profile-create__input" type="text" name="name" id="name" value="{{ old('name') }}">

            @foreach ($errors->get('name') as $message)
                <p class="profile-create__error">{{ $message }}</p>
            @endforeach
        </div>

        {{-- 郵便番号 --}}
        <div class="profile-create__group">
            <label class="profile-create__label" for="zip_code">郵便番号</label>
            <input class="profile-create__input" type="text" name="zip_code" id="zip_code" value="{{ old('zip_code') }}">

            @foreach ($errors->get('zip_code') as $message)
                <p class="profile-create__error">{{ $message }}</p>
            @endforeach
        </div>

        {{-- 住所 --}}
        <div class="profile-create__group">
            <label class="profile-create__label" for="residence">住所</label>
            <input class="profile-create__input" type="text" name="residence" id="residence" value="{{ old('residence') }}">

            @foreach ($errors->get('residence') as $message)
                <p class="profile-create__error">{{ $message }}</p>
            @endforeach
        </div>

        {{-- 建物名 --}}
        <div class="profile-create__group">
            <label class="profile-create__label" for="building">建物名</label>
            <input class="profile-create__input" type="text" name="building" id="building" value="{{ old('building') }}">

            @foreach ($errors->get('building') as $message)
                <p class="profile-create__error">{{ $message }}</p>
            @endforeach
        </div>

        <button class="profile-create__submit" type="submit">更新する</button>
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