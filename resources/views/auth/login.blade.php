<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Arsip Bawaslu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">

<div class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-md border-2 border-gray-100 p-7">

        <div style="text-align:center; margin-bottom:18px;">
            <h1 class="text-2xl font-bold text-gray-900 m-0">Login Arsip Bawaslu</h1>
            <p class="text-sm text-gray-500 mt-2">Sistem Pengelolaan Arsip — Bawaslu Kota Surabaya</p>
        </div>

        @if (session('error'))
            <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if (session('message'))
            <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
                {{ session('message') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700">Email / NIP</label>
                <input
                    id="email"
                    type="text"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Masukkan email atau NIP"
                    class="mt-1 block w-full rounded-xl border-gray-200 focus:border-red-500 focus:ring-red-500"
                >
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>

                <div class="relative">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="mt-1 block w-full rounded-xl border-gray-200 pr-12 focus:border-red-500 focus:ring-red-500"
                        placeholder="Masukkan password"
                    >

                    <button
                        type="button"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"
                        onclick="togglePassword()"
                        aria-label="Tampilkan password"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="eyeIcon">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <label class="inline-flex items-center">
                    <input
                        id="remember"
                        type="checkbox"
                        name="remember"
                        class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500"
                    >
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-[#b71b23] hover:bg-[#cd1720] text-white font-semibold py-2.5 rounded-xl">
                Log in
            </button>
        </form>

    </div>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (input.type === 'password') {
            input.type = 'text';
            eyeIcon.innerHTML = `
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                <line x1="1" y1="1" x2="23" y2="23"/>
            `;
        } else {
            input.type = 'password';
            eyeIcon.innerHTML = `
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
            `;
        }
    }
</script>

</body>
</html>

