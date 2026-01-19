<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifiedRedirect
{
    public function handle(Request $request, Closure $next)
    {
        // そもそも未ログインなら auth が処理するのでここでは通す
        $user = $request->user();
        if (!$user) {
            return $next($request);
        }

        // 未認証なら Fortify の認証誘導へ
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        // 認証済みなら通常通過
        return $next($request);
    }
}
