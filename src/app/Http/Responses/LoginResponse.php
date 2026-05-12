<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        //メール未認証の場合はメール認証誘導画面へ
        if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        //プロフィール未設定ならプロフィール設定画面へ
        if (! $user->profile) {
            return redirect()->route('profile.edit');
        }

        //プロフィール設定済みなら商品一覧へ
        return redirect()->route('products.index');
    }
}