<x-guest-layout>

    {{-- Judul halaman --}}
    <div style="text-align:center; margin-bottom:24px;">
        <h1 style="font-size:22px; font-weight:700; color:#111827; margin:0;">
            Login Arsip Bawaslu
        </h1>
        <p style="font-size:13px; color:#6b7280; margin:6px 0 0;">
            Sistem Pengelolaan Arsip — Bawaslu Kota Surabaya
        </p>

        @if (session('message'))
            <div class="mt-5 mb-5 p-4 rounded-md {{ session('status') == 'success' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200' }}">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-sm font-medium">{{ session('message') }}</p>
                </div>
            </div>
        @endif
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password + toggle mata -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <div style="position:relative;">
                <x-text-input id="password" class="block mt-1 w-full"
                    type="password" name="password"
                    required autocomplete="current-password"
                    style="padding-right:42px;" />
                <button type="button" onclick="togglePassword('password', 'eye-login')"
                    style="
                        position:absolute; right:10px; top:50%; transform:translateY(-50%);
                        background:none; border:none; cursor:pointer;
                        color:#9ca3af; padding:4px;
                    ">
                    <svg id="eye-login" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                        fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                    name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <!-- Forgot password + tombol login -->
        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

    </form>

    {{-- Link register --}}
    @if (Route::has('register'))
    <div style="text-align:center; margin-top:20px; padding-top:16px; border-top:1px solid #e5e7eb;">
        <span style="font-size:13px; color:#6b7280;">Belum punya akun? </span>
        <a href="{{ route('register') }}"
            style="font-size:13px; color:#185FA5; text-decoration:none; font-weight:600;">
            Daftar di sini
        </a>
    </div>
    @endif

</x-guest-layout>

<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);

    if (input.type === 'password') {
        input.type = 'text';
        // icon mata dicoret
        icon.innerHTML = `
            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
            <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
            <line x1="1" y1="1" x2="23" y2="23"/>
        `;
        icon.style.color = '#374151';
    } else {
        input.type = 'password';
        // icon mata normal
        icon.innerHTML = `
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
            <circle cx="12" cy="12" r="3"/>
        `;
        icon.style.color = '#9ca3af';
    }
}
</script>