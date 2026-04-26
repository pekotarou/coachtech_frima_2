@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
<div class="auth">
    <h1 class="auth__title">会員登録</h1>

    <form class="auth__form" method="POST" action="/register">
        @csrf

        {{-- 名前 --}}
        <div class="auth__group">
            <label class="auth__label">お名前</label>
            <input class="auth__input" type="text" name="name" value="{{ old('name') }}">
            @foreach ($errors->get('name') as $message)
                <p class="auth__error">{{ $message }}</p>
            @endforeach
        </div>

        {{-- メール --}}
        <div class="auth__group">
            <label class="auth__label">メールアドレス</label>
            <input class="auth__input" type="email" name="email" value="{{ old('email') }}">
            @foreach ($errors->get('email') as $message)
                <p class="auth__error">{{ $message }}</p>
            @endforeach
        </div>

        {{-- パスワード --}}
        <div class="auth__group">
            <label class="auth__label">パスワード</label>
            <input class="auth__input" type="password" name="password">
            @foreach ($errors->get('password') as $message)
                <p class="auth__error">{{ $message }}</p>
            @endforeach
        </div>

        {{-- 確認用 --}}
        <div class="auth__group">
            <label class="auth__label">確認用パスワード</label>
            <input class="auth__input" type="password" name="password_confirmation">
        </div>

        <button class="auth__button" type="submit">登録する</button>
    </form>

    <div class="auth__link">
        <a href="/login">ログインはこちら</a>
    </div>
</div>
@endsection