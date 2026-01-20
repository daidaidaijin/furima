<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ], [
            // name
            'name.required' => 'ユーザー名を入力してください',
            'name.max'      => 'ユーザー名は255文字以内で入力してください',

            // email
            'email.required' => 'メールアドレスを入力してください',
            'email.email'    => 'メールアドレスの形式で入力してください',
            'email.max'      => 'メールアドレスは255文字以内で入力してください',
            'email.unique'   => 'このメールアドレスは既に登録されています',

            // password（PasswordValidationRules の中身に合わせて必要なものだけ効く）
            'password.required'   => 'パスワードを入力してください',
            'password.min'        => 'パスワードは8文字以上で入力してください',
            'password.confirmed'  => 'パスワード（確認用）が一致しません',
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
