@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="address-edit">
    <h1 class="address-edit__title">
        住所の変更
    </h1>

    <form class="address-edit__form" action="{{ route('purchase.address.update', $product->id) }}" method="POST">
        @csrf

        <div class="address-edit__group">
            <label class="address-edit__label" for="zip_code">
                郵便番号
            </label>

            <input
                class="address-edit__input"
                type="text"
                name="zip_code"
                id="zip_code"
                value="{{ old('zip_code', optional($user->profile)->zip_code) }}"
            >

            @foreach ($errors->get('zip_code') as $message)
                <p class="address-edit__error">{{ $message }}</p>
            @endforeach
        </div>

        <div class="address-edit__group">
            <label class="address-edit__label" for="residence">
                住所
            </label>

            <input
                class="address-edit__input"
                type="text"
                name="residence"
                id="residence"
                value="{{ old('residence', optional($user->profile)->residence) }}"
            >

            @foreach ($errors->get('residence') as $message)
                <p class="address-edit__error">{{ $message }}</p>
            @endforeach
        </div>

        <div class="address-edit__group">
            <label class="address-edit__label" for="building">
                建物名
            </label>

            <input
                class="address-edit__input"
                type="text"
                name="building"
                id="building"
                value="{{ old('building', optional($user->profile)->building) }}"
            >

            @foreach ($errors->get('building') as $message)
                <p class="address-edit__error">{{ $message }}</p>
            @endforeach
        </div>

        <button class="address-edit__button" type="submit">
            更新する
        </button>
    </form>
</div>
@endsection