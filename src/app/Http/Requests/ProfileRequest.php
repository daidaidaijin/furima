<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png', 'max:2048'],
            'name' => ['required', 'string', 'max:20'],
            'postal_code' => ['required', 'string', 'size:8', 'regex:/^\d{3}-\d{4}$/'],
            'address_detail' => ['required', 'string', 'max:255'],
            'building' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'お名前を入力してください',
            'postal_code.required' => '郵便番号を入力してください',
            'address_detail.required' => '住所を入力してください',
            'postal_code.size' => '郵便番号はハイフンありの8文字で入力してください',
            'postal_code.regex' => '郵便番号はハイフンありの8文字で入力してください',
        ];
    }
}
