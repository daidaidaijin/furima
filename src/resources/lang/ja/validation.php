<?php

return [
    'required' => ':attributeを入力してください。',
    'email'    => ':attributeはメール形式で入力してください。',

    'min' => [
        'string'  => ':attributeは:min文字以上で入力してください。',
        'numeric' => ':attributeは:min以上で入力してください。',
        'array'   => ':attributeを選択してください。',
    ],

    'max' => [
        'string'  => ':attributeは:max文字以内で入力してください。',
        'numeric' => ':attributeは:max以下で入力してください。',
        'array'   => ':attributeは:max個以下にしてください。',
        'file'    => ':attributeは:max KB以内のファイルにしてください。',
    ],

    'size' => [
        'string' => ':attributeは:size文字で入力してください。',
    ],

    'confirmed' => ':attributeと一致しません。',
    'regex'     => ':attributeの形式が正しくありません。',
    'numeric'   => ':attributeは数値で入力してください。',
    'integer'   => ':attributeは整数で入力してください。',
    'in'        => ':attributeの選択が正しくありません。',
    'array'     => ':attributeを選択してください。',
    'image'     => ':attributeは画像ファイルを選択してください。',
    'mimes'     => ':attributeは:values形式のファイルを選択してください。',

    'attributes' => [
        'name' => 'お名前',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'password_confirmation' => 'パスワード（確認用）',

        'comment' => '商品コメント',
        'payment_method' => '支払い方法',

        'postal_code' => '郵便番号',
        'address' => '住所',
        'address_detail' => '住所',
        'building' => '建物名',

        'profile_image' => 'プロフィール画像',

        'title' => '商品名',
        'description' => '商品説明',
        'image' => '商品画像',
        'categories' => '商品のカテゴリー',
        'condition' => '商品の状態',
        'price' => '商品価格',
    ],
];
