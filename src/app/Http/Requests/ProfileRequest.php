<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //ニックネームは必須
            'name' => ['required', 'string', 'max:255'],
            //郵便番号は必須
            'zip_code' => ['required', 'string', 'max:8'],
            //住所は必須
            'residence' => ['required', 'string', 'max:255'],
            //建物名は任意
            'building' => ['nullable', 'string', 'max:255'],
            //画像は任意、画像ファイルのみ
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg','max:2048'],
            'image.max' => 'プロフィール画像は2MB以内でアップロードしてください。',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'お名前（ニックネーム）を入力してください。',
            'name.max' => 'お名前（ニックネーム）は255文字以内で入力してください。',
            'zip_code.required' => '郵便番号を入力してください。',
            'zip_code.max' => '郵便番号は8文字以内で入力してください。',
            'residence.required' => '住所を入力してください。',
            'residence.max' => '住所は255文字以内で入力してください。',
            'building.max' => '建物名は255文字以内で入力してください。',
            'image.image' => 'プロフィール画像は画像ファイルを選択してください。',
            'image.mimes' => 'プロフィール画像はjpeg、png、jpg形式でアップロードしてください。',
        ];
    }
}
