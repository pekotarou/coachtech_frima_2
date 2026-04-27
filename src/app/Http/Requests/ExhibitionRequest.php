<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            //商品画像は必須
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],

            //カテゴリーは複数選択
            'category_ids' => ['required', 'array', 'min:1'],
            'category_ids.*' => ['exists:categories,id'],

            //商品の状態
            'status_id' => ['required', 'exists:statuses,id'],

            //商品名
            'name' => ['required', 'string', 'max:255'],

            //ブランド名は任意
            'brand_name' => ['nullable', 'string', 'max:255'],
            //ここまで確認、以下修正する
            // 修正: 商品説明
            'description' => ['required', 'string', 'max:255'],

            // 修正: 販売価格
            'price' => ['required', 'integer', 'min:0'],
        ];
    }
}
