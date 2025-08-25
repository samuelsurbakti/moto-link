<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('ui.layouts.auth', [ 'image' => '/src/assets/illustrations/login.svg'])] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(AuthService $auth): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        try {
            $auth->attemptLogin($this->email, $this->password, $this->remember);
        } catch (ValidationException $e) {
            RateLimiter::hit($this->throttleKey());
            throw $e;
        }

        RateLimiter::clear($this->throttleKey());

        $this->redirectIntended(default: route('Access | Gate'));
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}; ?>

<div class="w-px-400 mx-auto mt-sm-12 mt-8">
    <h4 class="mb-1">Welcome to Sneat! 👋</h4>
    <p class="mb-6">Please sign-in to your account and start the adventure</p>

    <form wire:submit.prevent="login" class="mb-6">
        <x-ui::forms.input
            wire:model="email"
            type="text"
            id="email"
            name="email"
            label="Username atau Email"
            placeholder="emailanda@mail.com atau usernameanda"
            container_class="mb-6"
            autofocus
            required
        />

        <x-ui::forms.input-toggle
            id="password"
            name="password"
            label="Password"
            placeholder="••••••••"
            wire:model="password"
            container_class="form-password-toggle mb-6"
        />

        <div class="my-7">
            <div class="d-flex justify-content-between">
                <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" id="remember-me" wire:model="remember" />
                    <label class="form-check-label" for="remember-me">Remember Me</label>
                </div>
                @if (Route::has('password.request'))
                    <a href="auth-forgot-password-cover.html">
                        <p class="mb-0">Lupa Password?</p>
                    </a>
                @endif
            </div>
        </div>

        <x-ui::elements.button type="submit" class="btn-primary w-100 d-grid">
            Login
        </x-ui:elements.button>
    </form>
</div>
