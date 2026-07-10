<x-guest-layout>
    <div style="text-align:center; margin-bottom:24px;">
        <h1 style="font-size:22px; font-weight:700; color:#111827; margin:0;">
            Lupa Password
        </h1>
        <p style="font-size:13px; color:#6b7280; margin:6px 0 0;">
            Sistem Pengelolaan Arsip — Bawaslu Kota Surabaya
        </p>
    </div>

    <p style="font-size:13px; color:#6b7280; margin-bottom:16px; text-align:center;">
        Masukkan email terdaftar dan password baru Anda.
    </p>

    {{-- Error / Success Messages --}}
    @if ($errors->any())
        <div class="mb-4 p-4 rounded-md bg-red-100 text-red-700 border border-red-200">
            <div class="flex items-start">
                <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    @foreach ($errors->all() as $error)
                        <p class="text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('password.reset-pertanyaan') }}">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email Terdaftar')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="off" />
        </div>

        <!-- Password Baru -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password Baru')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ketik ulang password baru" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900 underline mr-3">
                Kembali ke Login
            </a>
            <x-primary-button>
                Reset Password
            </x-primary-button>
        </div>
    </form>

</x-guest-layout>