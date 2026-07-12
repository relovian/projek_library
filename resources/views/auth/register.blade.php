<x-guest-layout>

    {{-- Judul halaman --}}
    <div style="text-align:center; margin-bottom:24px;">
        <h1 style="font-size:22px; font-weight:700; color:#111827; margin:0;">
            Daftar Akun Baru
        </h1>
        <p style="font-size:13px; color:#6b7280; margin:6px 0 0;">
            Sistem Pengelolaan Arsip — Bawaslu Kota Surabaya
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Nama Lengkap -->
        <div>
            <x-input-label for="nama_lengkap" :value="__('Nama Lengkap')" />
            <span style="color:#ef4444; font-weight:bold;">*</span>
            <x-text-input id="nama_lengkap" class="block mt-1 w-full" type="text" name="nama_lengkap"
                :value="old('nama_lengkap')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
        </div>

        <!-- Nama Panggilan -->
        <div class="mt-4">
            <x-input-label for="nama_panggilan" :value="__('Nama Panggilan')" />
            <span style="color:#ef4444; font-weight:bold;">*</span>
            <x-text-input id="nama_panggilan" class="block mt-1 w-full" type="text" name="nama_panggilan"
                :value="old('nama_panggilan')" required />
            <x-input-error :messages="$errors->get('nama_panggilan')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password + toggle mata -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <div style="position:relative;">
                <x-text-input id="password" class="block mt-1 w-full"
                    type="password" name="password"
                    required autocomplete="new-password"
                    style="padding-right:42px;" />
                <button type="button" onclick="togglePassword('password', 'eye-password')"
                    style="
                        position:absolute; right:10px; top:50%; transform:translateY(-50%);
                        background:none; border:none; cursor:pointer;
                        color:#9ca3af; padding:4px;
                    ">
                    <svg id="eye-password" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                        fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password + toggle mata -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <div style="position:relative;">
                <x-text-input id="password_confirmation" class="block mt-1 w-full"
                    type="password" name="password_confirmation"
                    required autocomplete="new-password"
                    style="padding-right:42px;" />
                <button type="button" onclick="togglePassword('password_confirmation', 'eye-confirm')"
                    style="
                        position:absolute; right:10px; top:50%; transform:translateY(-50%);
                        background:none; border:none; cursor:pointer;
                        color:#9ca3af; padding:4px;
                    ">
                    <svg id="eye-confirm" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                        fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Tombol register + link login -->
        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>

    </form>

</x-guest-layout>

<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);

    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = `
            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
            <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
            <line x1="1" y1="1" x2="23" y2="23"/>
        `;
        icon.style.color = '#374151';
    } else {
        input.type = 'password';
        icon.innerHTML = `
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
            <circle cx="12" cy="12" r="3"/>
        `;
        icon.style.color = '#9ca3af';
    }
}
</script>