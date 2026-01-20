<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LoginViewResponse;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Http\Responses\SimpleViewResponse;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // ログイン画面を auth.login に固定
        $this->app->singleton(LoginViewResponse::class, function () {
            return new SimpleViewResponse('auth.login');
        });

        // 登録後のリダイレクトを verification.notice に固定
        $this->app->singleton(RegisterResponse::class, function () {
            return new class implements RegisterResponse {
                public function toResponse($request)
                {
                    return redirect()->route('verification.notice');
                }
            };
        });
    }

    public function boot(): void
    {
        // Fortifyの各アクション
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // Viewを指定（register/login/verify）
        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });

        /**
         * ★ログイン時のバリデーション文言を要件に合わせて上書き
         * さらに認証失敗時の文言もここで確定させる
         */
        Fortify::authenticateUsing(function (Request $request) {

            Validator::make($request->all(), [
                'email' => ['required', 'email'],
                'password' => ['required'],
            ], [
                'email.required' => 'メールアドレスを入力してください',
                'email.email' => 'メールアドレスの形式で入力してください',
                'password.required' => 'パスワードを入力してください',
            ])->validate();

            // 認証（失敗時メッセージも要件に合わせる）
            if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
                throw ValidationException::withMessages([
                    'email' => 'メールアドレスまたはパスワードが違います',
                ]);
            }

            $request->session()->regenerate();

            return Auth::user();
        });

        // レートリミット（そのまま）
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(
                Str::lower($request->input(Fortify::username())) . '|' . $request->ip()
            );

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
