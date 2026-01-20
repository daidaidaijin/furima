<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExhibitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'image' => ['required', 'image', 'mimes:jpeg,png', 'max:2048'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['integer'], // あると安心（任意）
            'condition' => [
                'required',
                Rule::in(['良好', '目立った傷や汚れなし', 'やや傷や汚れあり', '状態が悪い']),
            ],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => '商品名を入力してください',
            'description.required' => '商品の説明を入力してください',
            'image.required' => '商品画像を選択してください',
            'image.image' => '画像ファイルを選択してください',
            'image.mimes' => '画像はjpegまたはpng形式でアップロードしてください',
            'image.max' => '画像サイズは2MB以内にしてください',
            'categories.required' => 'カテゴリーを1つ以上選択してください',
            'categories.array' => 'カテゴリーの形式が不正です',
            'categories.min' => 'カテゴリーを1つ以上選択してください',
            'condition.required' => '商品の状態を選択してください',
            'condition.in' => '商品の状態が不正です',
            'price.required' => '販売価格を入力してください',
            'price.numeric' => '販売価格は数値で入力してください',
            'price.min' => '販売価格は0円以上で入力してください',
        ];
    }
}
