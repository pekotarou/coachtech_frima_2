<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * リクエストを許可するか
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * バリデーションルール
     *
     * @return array
     */
    public function rules()
    {
        //初回登録は画像必須、編集時は画像任意
        $imageRule = $this->isMethod('patch')
            ? ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048']
            : ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'];

        return [
            //ユーザー名は必須・20文字以内
            'name' => ['required', 'string', 'max:20'],

            //郵便番号はハイフンありの8文字 例: 123-4567
            'zip_code' => ['required', 'string', 'regex:/^\d{3}-\d{4}$/'],

            // 住所は必須
            'residence' => ['required', 'string', 'max:255'],

            // 建物名は任意
            'building' => ['nullable', 'string', 'max:255'],

            //初回登録/編集で画像ルールを切り替え
            'image' => $imageRule,
        ];
    }

    /**
     * エラーメッセージ
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'ユーザー名を入力してください。',
            'name.max' => 'ユーザー名は20文字以内で入力してください。',

            'zip_code.required' => '郵便番号を入力してください。',
            'zip_code.regex' => '郵便番号はハイフンありの8文字で入力してください。',

            'residence.required' => '住所を入力してください。',
            'residence.max' => '住所は255文字以内で入力してください。',

            'building.max' => '建物名は255文字以内で入力してください。',

            'image.required' => 'プロフィール画像をアップロードしてください。',
            'image.image' => 'プロフィール画像は画像ファイルを選択してください。',
            'image.mimes' => 'プロフィール画像はjpeg、png、jpg形式でアップロードしてください。',
            'image.max' => 'プロフィール画像は2MB以内でアップロードしてください。',

            //PHP側でアップロード失敗した時用
            'image.uploaded' => 'プロフィール画像は2MB以内でアップロードしてください。',
        ];
    }
}