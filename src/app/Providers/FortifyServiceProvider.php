<?php

namespace App\Providers;

//開発中のみログインの試行回数増やす（エラー文確認のため）
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Str;
//ここまで。試行回数をデフォにする場合は、上の4行消すこと


use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

//loginRequest.phpを使うためのuse文（以下3行）
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

//LoginResponse.phpを使うため
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use App\Http\Responses\LoginResponse;

//メール認証
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use App\Http\Responses\RegisterResponse;



class FortifyServiceProvider extends ServiceProvider
{
    public function register()
    {
        //ログイン成功後の遷移先をカスタム
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
        //会員登録後の遷移先をメール認証誘導画面にする
        $this->app->singleton(RegisterResponseContract::class, RegisterResponse::class);

    }

    public function boot()
    {
        //会員登録時に使うユーザー作成クラスを指定
        Fortify::createUsersUsing(CreateNewUser::class);

        //開発中だけログイン失敗回数の上限を増やす（不要になったら削除）
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(30)->by(
                Str::lower($email) . '|' . $request->ip()
            );
        });
        //試行回数を増やすのが不要になったら上記を消すこと

        //会員登録画面
        Fortify::registerView(function () {
            return view('auth.register');
        });


        //ログイン時に LoginRequest のバリデーションを使う
        Fortify::authenticateUsing(function (Request $request) {
            $loginRequest = new LoginRequest();

            validator(
                $request->all(),
                $loginRequest->rules(),
                $loginRequest->messages()
            )->validate();

            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }

            return null;
        });


        //ログイン画面
        Fortify::loginView(function () {
            return view('auth.login');
        });

        //メール認証誘導画面
        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });
    }
}