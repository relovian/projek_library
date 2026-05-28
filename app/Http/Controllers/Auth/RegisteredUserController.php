<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::min(8)->mixedCase()->numbers()],
        ], [
            // Pesan validasi Bahasa Indonesia
            'nama_lengkap.required'         => 'Nama lengkap wajib diisi.',
            'nama_lengkap.string'           => 'Nama harus berupa teks.',
            'nama_lengkap.max'              => 'Nama maksimal 255 karakter.',

            'email.required'        => 'Alamat email wajib diisi.',
            'email.string'          => 'Email harus berupa teks.',
            'email.lowercase'       => 'Email harus menggunakan huruf kecil.',
            'email.email'           => 'Format alamat email tidak valid.',
            'email.max'             => 'Email maksimal 255 karakter.',
            'email.unique'          => 'Email ini sudah terdaftar, silakan gunakan email lain.',

            'password.required'     => 'Kata sandi wajib diisi.',
            'password.confirmed'    => 'Konfirmasi kata sandi tidak cocok.',
            'password.min'          => 'Kata sandi minimal 8 karakter.',
            'password.mixed'        => 'Kata sandi harus mengandung huruf besar dan huruf kecil.',
            'password.numbers'      => 'Kata sandi harus mengandung minimal satu angka.',
        ]);

        $user = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'staff', // default role untuk semua pendaftar baru
        ]);

        event(new Registered($user));

        // Auth::login($user);

       // Di RegisteredUserController.php

        return redirect(route('login'))->with([
            'status' => 'success',
            'message' => 'Akun berhasil dibuat! Silakan masuk menggunakan email dan kata sandi Anda.'
        ]);
    }
}