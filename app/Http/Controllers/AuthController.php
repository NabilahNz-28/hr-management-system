<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman form login
    public function showLogin()
    {
        return view('auth.login'); // Mengarah ke resources/views/auth/login.blade.php
    }

    // Proses autentikasi login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();

            // AMBIL ROLE USER YANG BARU SAJA LOGIN
            $userRole = Auth::user()->role;

            // REDIRECT BERDASARKAN ROLE
            if ($userRole === 'superadmin') {
                // Arahkan Superadmin ke halaman Superadmin Dashboard
                return redirect()->intended('/superadmin/dashboard');
            } 
            elseif ($userRole === 'pic') {
                // Arahkan PIC ke halaman Dashboard Selection
                return redirect()->intended('/selection');
            } 
            elseif ($userRole === 'karyawan') {
                // Arahkan Karyawan ke halaman Dashboard Absensi
                return redirect()->intended('/dashboard');
            }

            // Fallback (jika role kosong atau tidak dikenali)
            return redirect('/');
        }

        // Jika login gagal, kembalikan ke halaman login beserta error
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
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
