<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            'password' => ['required', 'confirmed', 'min:6'],
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