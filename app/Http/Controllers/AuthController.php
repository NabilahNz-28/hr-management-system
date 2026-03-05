<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        // LANGSUNG return view, TIDAK ADA CHECK APAPUN
        return view('auth.login');
    }
    
    /**
     * Handle login - SIMPLE
     */
    public function login(Request $request)
    {
        // SIMPAN SESSION MANUAL
        session(['user_logged_in' => true]);
        session(['user_name' => 'Test User']);
        
        // Redirect ke dashboard
        return redirect()->route('dashboard.index');
    }
    
    /**
     * Show registration form
     */
    public function showRegister()
    {
        return view('auth.register');
    }
    
    /**
     * Handle registration - SIMPLE
     */
    public function register(Request $request)
    {
        // SIMPAN SESSION MANUAL
        session(['user_logged_in' => true]);
        session(['user_name' => $request->name]);
        
        return redirect()->route('dashboard');
    }
    
    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        // HAPUS SESSION
        session()->forget(['user_logged_in', 'user_name']);
        
        return redirect('/');
    }
}