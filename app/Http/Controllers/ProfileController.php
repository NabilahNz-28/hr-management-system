<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }
    
    public function update(Request $request)
    {
        return redirect()->back()->with('success', 'Profile berhasil diperbarui');
    }
}