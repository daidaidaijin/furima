<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * プロフィール編集画面
     */
    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    /**
     * プロフィール更新
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        /**
         * バリデーション方針
         * - 未入力はあなた指定の文言を使う（required）
         * - それ以外（max/min等）は resources/lang/ja/validation.php の共通文言を使う
         * - 郵便番号は形式チェック（1234567 or 123-4567）
         */
        $validated = $request->validate(
            [
                'profile_image'  => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],

                'name'           => ['required', 'string', 'max:100'],

                // 郵便番号：10文字じゃなく、実態に合わせて max:8 にすると綺麗
                // 1234567 / 123-4567 を許可
                'postal_code'    => ['required', 'string', 'max:8', 'regex:/^\d{3}-?\d{4}$/'],

                // 住所：短すぎる入力を弾く（例: うんち は3文字なので落ちる）
                'address_detail' => ['required', 'string', 'min:5', 'max:255'],

                'building'       => ['nullable', 'string', 'max:255'],
            ],
            [
                // required 系はあなたの指定文言に固定
                'name.required'           => 'お名前を入力してください',
                'postal_code.required'    => '郵便番号を入力してください',
                'address_detail.required' => '住所を入力してください',

                // ここだけは個別に出した方が親切なので上書き
                'postal_code.max'   => '郵便番号は8文字以内で入力してください',
                'postal_code.regex' => '郵便番号は「1234567」または「123-4567」の形式で入力してください',

                'address_detail.min' => '住所は5文字以上で入力してください',
            ]
        );

        // 更新データ
        $updateData = [
            'name'           => $validated['name'],
            'postal_code'    => $validated['postal_code'],
            'address_detail' => $validated['address_detail'],
            'building'       => $validated['building'] ?? null,
        ];

        // 画像があれば保存
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
            $updateData['profile_image'] = $path;
        }

        // 更新
        $user->update($updateData);

        // 遷移
        return redirect()->route('home');
        // return redirect()->route('items.index');
    }
}
