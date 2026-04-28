<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // 修正: 郵便番号はハイフンありの8文字 例: 123-4567
            'zip_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],

            // 修正: 住所は必須
            'residence' => ['required', 'string', 'max:255'],

            // 修正: 建物名は任意
            'building' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'zip_code.required' => '郵便番号を入力してください。',
            'zip_code.regex' => '郵便番号はハイフンありの8文字で入力してください。',

            'residence.required' => '住所を入力してください。',
            'residence.max' => '住所は255文字以内で入力してください。',

            'building.max' => '建物名は255文字以内で入力してください。',
        ];
    }
}