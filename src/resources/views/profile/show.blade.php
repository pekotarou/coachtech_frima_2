@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
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
</div>
@endsection