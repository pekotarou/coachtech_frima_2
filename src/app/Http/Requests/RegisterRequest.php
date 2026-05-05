<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Actions\Fortify\PasswordValidationRules;

class RegisterRequest extends FormRequest
{
    use PasswordValidationRules;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            //会員登録時のユーザー名は20文字以内
            'name' => ['required', 'string', 'max:20'],

            //メールアドレス
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],

            //パスワード
            'password' => $this->passwordRules(),
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'お名前を入力してください',
            'name.max' => 'お名前は20文字以内で入力してください',

            'email.required' => 'メールアドレスを入力してください',
            'email.email' => 'メールアドレスはメール形式で入力してください',
            'email.max' => 'メールアドレスは255文字以内で入力してください',
            'email.unique' => 'このメールアドレスはすでに登録されています',

            'password.required' => 'パスワードを入力してください',
            'password.min' => 'パスワードは8文字以上で入力してください',
            'password.confirmed' => 'パスワードと一致しません',//viewでは確認用パスワード下にこれが出るようにする
        ];
    }
}