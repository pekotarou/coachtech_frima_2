<?php

namespace App\Actions\Fortify;

use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    protected function passwordRules()
    {
        return [
            //8文字以上 + 確認用パスワード一致
            'required',
            'string',
            'min:8',
            'confirmed',
        ];
    }
}