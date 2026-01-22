<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login
     */
    public function showLoginForm()
    {
        // Jika user sudah login, redirect ke dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Proses login user
     */
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi harus diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
        ]);

        // Cek status user aktif
        $user = \App\Models\User::where('email', $credentials['email'])->first();
        
        if ($user && $user->status !== 'aktif') {
            throw ValidationException::withMessages([
                'email' => 'Akun Anda sedang tidak aktif. Hubungi admin.',
            ]);
        }

        // Attempt login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Log audit untuk login
            \App\Models\AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'login',
                'entity_type' => 'User',
                'entity_id' => Auth::id(),
                'description' => 'User login ke sistem',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Redirect ke intended page atau dashboard
            return redirect()->intended(route('dashboard'))
                ->with('status', 'Selamat datang ' . Auth::user()->name . '!');
        }

        // Login gagal
        throw ValidationException::withMessages([
            'email' => 'Email atau kata sandi tidak sesuai.',
        ]);
    }

    /**
     * Proses logout user
     */
    public function logout(Request $request)
    {
        // Log audit untuk logout
        if (Auth::check()) {
            \App\Models\AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'logout',
                'entity_type' => 'User',
                'entity_id' => Auth::id(),
                'description' => 'User logout dari sistem',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Anda telah logout.');
    }
}
