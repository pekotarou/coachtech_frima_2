<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * ログイン成功後の遷移先
     */
    public function toResponse($request)
    {
        // 修正: ログイン成功後は商品一覧画面トップへ遷移
        return redirect('/');
    }
}