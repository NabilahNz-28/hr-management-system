<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Lockout;

class AuthController extends Controller
{
    // Menampilkan halaman form login
    public function showLogin()
    {
        return view('auth.login'); // Mengarah ke resources/views/auth/login.blade.php
    }

    // Aturan kekuatan password (dipakai register & reset password)
    private function passwordRules(): array
    {
        return [
            'required',
            'confirmed',
            PasswordRule::min(6)   // minimal 6 karakter
                ->letters()        // wajib ada huruf
                ->mixedCase()      // wajib ada huruf besar & kecil
                ->numbers()        // wajib ada angka
                ->symbols(),       // wajib ada simbol
        ];
    }

    // ===== REGISTER =====
    // Pendaftaran TIDAK terbuka untuk publik. Akun hanya dibuat oleh Superadmin
    // melalui menu Data Karyawan (lihat SuperadminController@karyawanStore).
    public function showRegister()
    {
        return redirect()->route('login')
            ->with('error', 'Pendaftaran akun hanya dapat dilakukan melalui HRD / Superadmin.');
    }

    // Maksimal percobaan login gagal sebelum dikunci sementara
    private const MAX_LOGIN_ATTEMPTS = 5;

    // Lama jendela penguncian (detik)
    private const LOCKOUT_SECONDS = 60;

    // Membuat kunci throttle unik per kombinasi email + IP
    private function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());
    }

    // Proses autentikasi login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $key = $this->throttleKey($request);

        // Jika sudah melewati batas percobaan -> tolak & beri sisa waktu
        if (RateLimiter::tooManyAttempts($key, self::MAX_LOGIN_ATTEMPTS)) {
            event(new Lockout($request));
            $seconds = RateLimiter::availableIn($key);

            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Login sukses -> bersihkan hitungan percobaan
            RateLimiter::clear($key);
            $request->session()->regenerate();

            // AMBIL ROLE USER YANG BARU SAJA LOGIN
            $userRole = Auth::user()->role;

            // REDIRECT BERDASARKAN ROLE
            if ($userRole === 'superadmin') {
                return redirect()->intended(route('dashboard.superadmin'));
            } elseif ($userRole === 'pic') {
                return redirect()->intended(route('dashboard.selection'));
            } elseif ($userRole === 'karyawan') {
                return redirect()->intended(route('dashboard.absensi'));
            }

            // Fallback (jika role kosong atau tidak dikenali)
            return redirect('/');
        }

        // Login gagal -> catat percobaan (decay sesuai LOCKOUT_SECONDS)
        RateLimiter::hit($key, self::LOCKOUT_SECONDS);

        $remaining = self::MAX_LOGIN_ATTEMPTS - RateLimiter::attempts($key);
        $message = 'Email atau password salah.';
        if ($remaining > 0 && $remaining <= 2) {
            $message .= " Sisa {$remaining} percobaan sebelum akun dikunci sementara.";
        }

        return back()->withErrors(['email' => $message])->onlyInput('email');
    }

    // ===== LUPA PASSWORD =====

    // Form "Lupa Password" (input email)
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    // Kirim link reset ke email
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Link reset password telah dikirim ke email Anda.')
            : back()->withErrors(['email' => 'Email tidak terdaftar atau gagal mengirim.'])->onlyInput('email');
    }

    // Form reset password (dari link di email)
    public function showResetPassword(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    // Proses simpan password baru
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => $this->passwordRules(),
        ], [
            'token.required'     => 'Token wajib ada.',
            'email.required'     => 'Email wajib diisi.',
            'password.required'  => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.min'       => 'Password minimal 6 karakter.',
            'password.letters'   => 'Password wajib mengandung huruf.',
            'password.mixed'     => 'Password wajib mengandung minimal 1 huruf kapital (besar) dan 1 huruf kecil.',
            'password.numbers'   => 'Password wajib mengandung minimal 1 angka.',
            'password.symbols'   => 'Password wajib mengandung minimal 1 simbol.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password berhasil diubah. Silakan login.')
            : back()->withErrors(['email' => 'Token tidak valid atau sudah kedaluwarsa.'])->onlyInput('email');
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah berhasil logout.');
    }
}
