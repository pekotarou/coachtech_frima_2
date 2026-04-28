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

            //商品説明
            'description' => ['required', 'string', 'max:255'],

            //販売価格
            'price' => ['required', 'integer', 'min:0'],
        ];
    }


     public function messages()
    {
        return [
            'image.required' => '商品画像をアップロードしてください。',
            'image.image' => '商品画像は画像ファイルを選択してください。',
            'image.mimes' => '商品画像はjpeg、png、jpg形式でアップロードしてください。',
            'image.max' => '商品画像は2MB以内でアップロードしてください。',
            'image.uploaded' => '商品画像は2MB以内でアップロードしてください。',

            'category_ids.required' => 'カテゴリーを選択してください。',
            'category_ids.array' => 'カテゴリーを選択してください。',
            'category_ids.min' => 'カテゴリーを選択してください。',
            'category_ids.*.exists' => '選択されたカテゴリーが正しくありません。',

            'status_id.required' => '商品の状態を選択してください。',
            'status_id.exists' => '選択された商品の状態が正しくありません。',

            'name.required' => '商品名を入力してください。',
            'name.max' => '商品名は255文字以内で入力してください。',

            'brand_name.max' => 'ブランド名は255文字以内で入力してください。',

            'description.required' => '商品説明を入力してください。',
            'description.max' => '商品説明は255文字以内で入力してください。',

            'price.required' => '販売価格を入力してください。',
            'price.integer' => '販売価格は数値で入力してください。',
            'price.min' => '販売価格は0円以上で入力してください。',
        ];
    }
}
