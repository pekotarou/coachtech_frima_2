<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_会員登録後に認証メールが送信される()
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where('email', 'test@example.com')->first();

        $this->assertNotNull($user);

        // 修正: 認証メールが送信されているか確認
        Notification::assertSentTo($user, VerifyEmail::class);

        // 修正: 会員登録後はメール認証誘導画面へ遷移
        $response->assertRedirect(route('verification.notice'));
    }

    public function test_未認証ユーザーがログインするとメール認証誘導画面へ遷移する()
    {
        $user = User::factory()->create([
            'email' => 'unverified@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => null,
        ]);

        $response = $this->post('/login', [
            'email' => 'unverified@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);

        // 修正: 未認証ユーザーはメール認証誘導画面へ
        $response->assertRedirect(route('verification.notice'));
    }

    public function test_認証済みユーザーがログインするとプロフィール設定画面へ遷移する()
    {
        $user = User::factory()->create([
            'email' => 'verified@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => 'verified@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);

        // 修正: 認証済みかつプロフィール未設定ならプロフィール設定画面へ
        $response->assertRedirect(route('profile.edit'));
    }
}