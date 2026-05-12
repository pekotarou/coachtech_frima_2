@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
<div class="auth">
    <p class="auth__message">
        登録していただいたメールアドレスに認証メールを送付しました。<br>
        メール認証を完了してください。
    </p>

    <a class="auth__button-link" href="http://localhost:8025" target="_blank">
        認証はこちらから
    </a>

    <form class="auth__resend-form" method="POST" action="{{ route('verification.send') }}">
        @csrf

        <button class="auth__resend-button" type="submit">
            認証メールを再送する
        </button>
    </form>
</div>
@endsection