<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        //会員登録後はメール認証誘導画面へ遷移する
        return redirect()->route('verification.notice');
    }
}