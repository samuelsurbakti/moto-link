<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AuthService
{
    /**
     * Attempt to login using username or email.
     */
    public function attemptLogin(string $login, string $password, bool $remember = false): User
    {
        $user = User::query()
            ->where('email', $login)
            ->orWhere('username', $login)
            ->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        if (! $user->account_status) {
            throw ValidationException::withMessages([
                'email' => __('auth.account_inactive'), // nanti kita tambahkan di lang
            ]);
        }

        Auth::login($user, $remember);
        Session::regenerate();

        return $user;
    }

    /**
     * Generate throttle key.
     */
    public function throttleKey(string $login): string
    {
        return Str::transliterate(Str::lower($login) . '|' . request()->ip());
    }
}
