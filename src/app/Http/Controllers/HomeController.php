<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // プロフィール未設定ならプロフィール編集へ
        if (is_null($user->profile_image)) {
            return redirect()->route('profile.edit');
        }

        // 設定済みなら通常ホーム
         return redirect()->route('items.index');
    }
}
