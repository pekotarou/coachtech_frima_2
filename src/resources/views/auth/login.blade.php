@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
<div class="auth">
    <h1 class="auth__title">ログイン</h1>

    <form class="auth__form" method="POST" action="/login">
        @csrf
        {{-- メール --}}
        <div class="auth__group">
            <label class="auth__label">メールアドレス</label>
            <input 
                class="auth__input" 
                type="email" 
                name="email" 
                id="email"
                value="{{ old('email') }}"
            >
            @foreach ($errors->get('email') as $message)
                <p class="auth__error">{{ $message }}</p>
            @endforeach
        </div>

        {{-- パスワード --}}
        <div class="auth__group">
            <label class="auth__label" for="password">パスワード</label>
            <input 
                class="auth__input" 
                type="password" 
                name="password"
                id="password"
            >
            <!--パスワードエラー表示-->
            @foreach ($errors->get('password') as $message)
                <p class="auth__error">{{ $message }}</p>
            @endforeach
        </div>

        <button class="auth__button" type="submit">ログインする</button>

    </form>

    <div class="auth__link">
        <a href="/register">会員登録はこちら</a>
    </div>
</div>
@endsection