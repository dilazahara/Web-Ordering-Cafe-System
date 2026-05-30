<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    // =========================================
    // FORM LOGIN
    // =========================================
    public function index()
    {
        // Jika sudah login, redirect sesuai role
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }

        return view('login');
    }

    // =========================================
    // PROSES LOGIN
    // =========================================
    public function login(Request $request)
    {
        // VALIDASI INPUT
        $request->validate([
            'email'    => 'required|email|max:255',
            'password' => 'required|string|min:6',
            'role'     => 'required|string|in:Admin,Kasir,Dapur,Pelayan',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.max'         => 'Email terlalu panjang.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
            'role.required'     => 'Silakan pilih role terlebih dahulu.',
            'role.in'           => 'Role yang dipilih tidak valid.',
        ]);

        // RATE LIMITING — maks 5 percobaan per menit per IP+email
        $throttleKey = Str::lower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()
                ->withErrors(['email' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik."])
                ->withInput($request->only('email', 'role'));
        }

        // ATTEMPT LOGIN
        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {

            // Reset rate limiter setelah berhasil login
            RateLimiter::clear($throttleKey);

            // Regenerate session token
            $request->session()->regenerate();

            $user = Auth::user();

            // Cek apakah user aktif (jika kolom is_active ada)
            if (property_exists($user, 'is_active') && !$user->is_active) {
                Auth::logout();
                return back()
                    ->withErrors(['email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.'])
                    ->withInput($request->only('email', 'role'));
            }

            // =========================================
            // VALIDASI ROLE — Akun harus sesuai role yang dipilih
            // =========================================
            $selectedRole = Str::lower($request->input('role')); // "kasir", "admin", dll
            $userRole     = Str::lower($user->role);             // role di database

            if ($selectedRole !== $userRole) {
                Auth::logout();
                $roleLabels = [
                    'admin'   => 'Admin',
                    'kasir'   => 'Kasir',
                    'dapur'   => 'Dapur',
                    'pelayan' => 'Pelayan',
                ];
                $selectedLabel = $roleLabels[$selectedRole] ?? ucfirst($selectedRole);
                return back()
                    ->withErrors([
                        'role_mismatch' => "Akun ini bukan akun {$selectedLabel}. Silakan pilih role yang sesuai dengan akun Anda."
                    ])
                    ->withInput($request->only('email', 'role'));
            }

            return $this->redirectByRole($user->role);
        }

        // LOGIN GAGAL — tambah hitungan rate limiter
        RateLimiter::hit($throttleKey, 60);

        $remaining = 5 - RateLimiter::attempts($throttleKey);

        $message = 'Email atau password yang kamu masukkan salah.';
        if ($remaining <= 2 && $remaining > 0) {
            $message .= " Sisa {$remaining} percobaan sebelum akun dikunci sementara.";
        }

        return back()
            ->withErrors(['email' => $message])
            ->withInput($request->only('email', 'role'));
    }

    // =========================================
    // REDIRECT BERDASARKAN ROLE
    // =========================================
    private function redirectByRole(string $role)
    {
        $redirectMap = [
            'admin'   => '/admin/dashboard',
            'kasir'   => '/kasir/dashboard',
            'pelayan' => '/pelayan/antar',
            'dapur'   => '/dapur/proses',
        ];

        if (array_key_exists($role, $redirectMap)) {
            return redirect($redirectMap[$role])
                ->with('login_success', true);
        }

        // Role tidak dikenali → logout
        Auth::logout();
        return redirect('/login')
            ->withErrors(['email' => 'Akun Anda tidak memiliki hak akses yang valid.']);
    }

    // =========================================
    // LOGOUT
    // =========================================
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Berhasil keluar. Sampai jumpa!');
    }
}