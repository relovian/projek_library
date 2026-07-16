<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Login bisa menggunakan email atau NIP
        $credentials = $this->only('email', 'password');
        $loginValue = $credentials['email'];

        // Cek apakah input berupa NIP (18 digit angka) atau email
        if (preg_match('/^\d{18}$/', $loginValue)) {
            // Login menggunakan NIP
            $field = 'nip';
        } else {
            // Login menggunakan email
            $field = 'email';
        }

        // 1) Cek user berdasarkan kredensial (tanpa password)
        $userQuery = \App\Models\User::query();
        $userQuery->where($field, $loginValue);
        $user = $userQuery->first();

        // 2) Pesan custom: user tidak ditemukan / password salah / user nonaktif
        //    Catatan: Auth::attempt akan tetap hashing password.
        if (!$user) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Email atau NIP tidak sesuai.',
            ]);
        }

        if (!$user->is_aktif) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Akun Anda telah dinonaktifkan. Silakan hubungi admin.',
            ]);
        }

        if (! Auth::attempt([
            $field => $loginValue,
            'password' => $credentials['password'],
            'is_aktif' => true,
        ], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Password salah. Silakan coba lagi.',
            ]);
        }


        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}