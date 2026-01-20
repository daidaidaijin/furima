<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 設計書：ハイフンあり8文字（123-4567）
            'postal_code' => ['required', 'string', 'size:8', 'regex:/^\d{3}-\d{4}$/'],

            // 設計書：住所は必須
            'address' => ['required', 'string', 'max:255'],

            // buildingは任意（設計書に明示がないので、既存仕様に合わせる）
            'building' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'postal_code.required' => '郵便番号を入力してください',
            'postal_code.size'     => '郵便番号はハイフンありの8文字で入力してください',
            'postal_code.regex'    => '郵便番号はハイフンありの8文字で入力してください',

            'address.required'     => '住所を入力してください',
        ];
    }

    public function attributes(): array
    {
        return [
            'postal_code' => '郵便番号',
            'address' => '住所',
            'building' => '建物名',
        ];
    }
}
