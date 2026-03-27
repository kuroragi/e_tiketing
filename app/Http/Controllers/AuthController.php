<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Proses login user dengan rate limiting & full audit
     */
    public function login(Request $request)
    {
        // Validasi input dasar
        $credentials = $request->validate([
            'email'    => 'required|email|max:255',
            'password' => 'required|string|min:6',
        ], [
            'email.required'    => 'Email harus diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Kata sandi harus diisi.',
            'password.min'      => 'Kata sandi minimal 6 karakter.',
        ]);

        // ── Rate Limiting berbasis email + IP ──────────────────────────────────
        $throttleKey = $this->throttleKey($request);

        if (RateLimiter::tooManyAttempts($throttleKey, $maxAttempts = 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            // Catat percobaan setelah di-block ke audit log
            $this->logFailedLogin($request, 'Terblokir rate limiter');

            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        // ── Cek apakah akun user aktif ────────────────────────────────────────
        $user = User::where('email', $credentials['email'])->first();

        if ($user && $user->status !== 'aktif') {
            RateLimiter::hit($throttleKey, $decaySeconds = 60);
            $this->logFailedLogin($request, 'Akun tidak aktif', $user->id);

            throw ValidationException::withMessages([
                'email' => 'Akun Anda sedang tidak aktif. Hubungi admin.',
            ]);
        }

        // ── Attempt login ──────────────────────────────────────────────────────
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Reset rate limiter setelah berhasil login
            RateLimiter::clear($throttleKey);

            $request->session()->regenerate();

            // Catat login berhasil
            AuditLog::create([
                'user_id'     => Auth::id(),
                'action'      => 'login',
                'entity_type' => 'User',
                'entity_id'   => Auth::id(),
                'entity_name' => Auth::user()->name,
                'description' => 'User berhasil login ke sistem',
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
            ]);

            // Hindari redirect SKPD/Petugas ke halaman admin
            $authUser  = Auth::user();
            $intended  = $request->session()->get('url.intended', '');
            $intendedPath = parse_url($intended, PHP_URL_PATH) ?? '';
            if (! $authUser->isAdmin() && str_starts_with($intendedPath, '/admin')) {
                $request->session()->forget('url.intended');
            }

            return redirect()->intended(route('dashboard'))
                ->with('status', 'Selamat datang, ' . $authUser->name . '!');
        }

        // ── Login gagal ────────────────────────────────────────────────────────
        RateLimiter::hit($throttleKey, $decaySeconds = 60);

        $attemptsLeft = $maxAttempts - RateLimiter::attempts($throttleKey);
        $this->logFailedLogin($request, 'Kredensial salah');

        $message = 'Email atau kata sandi tidak sesuai.';
        if ($attemptsLeft > 0 && $attemptsLeft <= 2) {
            $message .= " ({$attemptsLeft} percobaan tersisa sebelum diblokir)";
        }

        throw ValidationException::withMessages([
            'email' => $message,
        ]);
    }

    /**
     * Proses logout user
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            AuditLog::create([
                'user_id'     => Auth::id(),
                'action'      => 'logout',
                'entity_type' => 'User',
                'entity_id'   => Auth::id(),
                'entity_name' => Auth::user()->name,
                'description' => 'User logout dari sistem',
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Anda telah logout.');
    }

    // ── Private Helpers ────────────────────────────────────────────────────────

    /**
     * Kunci rate limiter unik per kombinasi email + IP.
     */
    private function throttleKey(Request $request): string
    {
        return Str::lower($request->input('email')) . '|' . $request->ip();
    }

    /**
     * Catat percobaan login gagal ke AuditLog.
     *
     * @param Request $request
     * @param string  $reason  Alasan kegagalan
     * @param int|null $userId  ID user jika diketahui (misalnya akun nonaktif)
     */
    private function logFailedLogin(Request $request, string $reason, ?int $userId = null): void
    {
        AuditLog::create([
            'user_id'     => $userId,
            'action'      => 'login_failed',
            'entity_type' => 'User',
            'entity_id'   => $userId ?? 0,
            'entity_name' => $request->input('email'),
            'description' => "Login gagal untuk {$request->input('email')}: {$reason}",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);
    }
}
