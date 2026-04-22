<?php

namespace App\Services\Vibe\Stage04;

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserAccountService
{
    public function overview(): array
    {
        return [
            'stage' => 'Stage 04',
            'scope' => [
                'register',
                'login',
                'logout',
                'profile',
                'forgot_password',
                'reset_password',
                'social_login',
            ],
            'notes' => [
                'UI duoc xem la co san, backend tra JSON de tich hop vao giao dien.',
                'Social login duoc demo bang callback payload de phase co the chay test offline.',
            ],
        ];
    }

    public function register(array $payload): User
    {
        return User::query()->create([
            'name' => $payload['name'],
            'email' => $payload['email'],
            'password' => Hash::make($payload['password']),
            'phone' => $payload['phone'] ?? null,
            'address' => $payload['address'] ?? null,
            'status' => true,
        ]);
    }

    public function login(array $payload, bool $remember = false): User
    {
        $guard = Auth::guard('web');

        $isLoggedIn = $guard->attempt([
            'email' => $payload['email'],
            'password' => $payload['password'],
        ], $remember);

        if (! $isLoggedIn) {
            throw ValidationException::withMessages([
                'email' => ['Email hoac mat khau khong dung.'],
            ]);
        }

        /** @var User $user */
        $user = $guard->user();

        if (! $user->status) {
            $guard->logout();

            throw ValidationException::withMessages([
                'email' => ['Tai khoan da bi khoa.'],
            ]);
        }

        return $user;
    }

    public function logout(): void
    {
        Auth::guard('web')->logout();
    }

    public function updateProfile(User $user, array $payload): User
    {
        $data = [
            'name' => $payload['name'],
            'email' => $payload['email'],
            'phone' => $payload['phone'] ?? null,
            'address' => $payload['address'] ?? null,
        ];

        if (! empty($payload['password'])) {
            $data['password'] = Hash::make($payload['password']);
        }

        $user->fill($data);
        $user->save();

        return $user->fresh();
    }

    public function createPasswordResetToken(string $email): array
    {
        $token = Str::random(64);

        DB::table('password_resets')->where('email', $email)->delete();
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return [
            'email' => $email,
            'delivery' => 'demo-inline',
            'reset_payload' => [
                'email' => $email,
                'token' => $token,
            ],
        ];
    }

    public function resetPassword(array $payload): void
    {
        $resetRow = DB::table('password_resets')
            ->where('email', $payload['email'])
            ->where('token', $payload['token'])
            ->first();

        if ($resetRow === null) {
            throw ValidationException::withMessages([
                'token' => ['Token dat lai mat khau khong hop le.'],
            ]);
        }

        User::query()
            ->where('email', $payload['email'])
            ->update([
                'password' => Hash::make($payload['password']),
            ]);

        DB::table('password_resets')->where('email', $payload['email'])->delete();
    }

    public function socialLogin(array $payload): array
    {
        $account = SocialAccount::query()
            ->where('provider', $payload['provider'])
            ->where('provider_user_id', $payload['provider_user_id'])
            ->first();

        $userCreated = false;
        $linkCreated = false;

        if ($account) {
            $user = $account->user;
        } else {
            $user = User::query()->where('email', $payload['email'])->first();

            if (! $user) {
                $user = User::query()->create([
                    'name' => $payload['name'] ?? $payload['email'],
                    'email' => $payload['email'],
                    'password' => Hash::make(Str::random(32)),
                    'status' => true,
                ]);
                $userCreated = true;
            }

            DB::table('social_accounts')->insert([
                'user_id' => $user->id,
                'provider_user_id' => $payload['provider_user_id'],
                'provider' => $payload['provider'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $linkCreated = true;
        }

        if (! $user->status) {
            throw ValidationException::withMessages([
                'email' => ['Tai khoan da bi khoa.'],
            ]);
        }

        Auth::guard('web')->login($user, true);

        return [
            'user' => $this->toProfileArray($user),
            'user_created' => $userCreated,
            'link_created' => $linkCreated,
        ];
    }

    public function toProfileArray(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'address' => $user->address,
            'status' => (bool) $user->status,
        ];
    }
}
