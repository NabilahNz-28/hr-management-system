<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('absensi.pengaturan.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'alamat' => ['required', 'string', 'min:10', 'max:500'],
        ]);

        $user = Auth::user();
        $user->alamat = $request->alamat;
        $user->save();

        return redirect()->back()->with('success', 'Alamat berhasil diperbarui');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Password::min(6)->letters()->mixedCase()->numbers()->symbols()],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required'         => 'Password baru wajib diisi.',
            'password.confirmed'        => 'Konfirmasi password baru tidak cocok.',
            'password.min'              => 'Password minimal 6 karakter.',
            'password.letters'          => 'Password wajib mengandung huruf.',
            'password.mixed'            => 'Password wajib mengandung minimal 1 huruf kapital (besar) dan 1 huruf kecil.',
            'password.numbers'          => 'Password wajib mengandung minimal 1 angka.',
            'password.symbols'          => 'Password wajib mengandung minimal 1 simbol.'
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors([
                'current_password' => 'Password lama tidak sesuai.',
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Password berhasil diubah.');
    }
}