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

        // ① バリデーション（建物名だけ任意）
        $validated = $request->validate(
            [
                'profile_image'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'name'           => 'required|string|max:100',
                'postal_code'    => 'required|string|max:10',
                'address_detail' => 'required|string|max:255',
                'building'       => 'nullable|string|max:255',
            ],
            [
                'name.required'           => 'お名前を入力してください',
                'postal_code.required'    => '郵便番号を入力してください',
                'address_detail.required' => '住所を入力してください',
            ]
        );

        // ② 更新データ
        $updateData = [
            'name'           => $validated['name'],
            'postal_code'    => $validated['postal_code'],
            'address_detail' => $validated['address_detail'],
            'building'       => $validated['building'] ?? null,
        ];

        // ③ 画像があれば保存
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
            $updateData['profile_image'] = $path;
        }

        // ④ 更新
        $user->update($updateData);

        // ⑤ 遷移（homeが無いなら items.index にしてOK）
        return redirect()->route('home');
        // return redirect()->route('items.index');
    }
}
