<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password form.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Verify email exists and reset password directly.
     */
    public function reset(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email tidak ditemukan dalam sistem.']);
        }

        // Update password
        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ])->save();

        return redirect()->route('login')
            ->with('message', 'Password berhasil direset. Silakan login dengan password baru Anda.')
            ->with('status', 'success');
    }
}