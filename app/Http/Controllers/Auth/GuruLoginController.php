<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

// Controller Login untuk Guru
class GuruLoginController extends Controller
{
    // Tampilkan form login guru
    public function showLoginForm()
    {
        return view('auth.guru-login');
    }

    public function login(Request $request)
    {
        // Validasi Email dan Password
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Coba login (attempt) dengan kredensial yang diberikan
        // $request->remember untuk fitur "Remember Me"
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            // Jika berhasil login, cek role user
            $user = Auth::user();
            if ($user->role && $user->role->nama_role === 'Guru') {
                // Jika role Guru, redirect ke dashboard Guru (Filament Dashboard)
                return redirect()->intended(route('filament.guru.pages.dashboard'));
            }
            // Jika login sukses tapi bukan Guru (misal Admin login di form Guru), logic logout/error bisa ditambahkan di sini
            // Saat ini flow akan lanjut ke return back() jika if di atas tidak return
        }

        // Login gagal
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('guru.login');
    }

    // --- Forgot Password Features ---

    // Form input email untuk reset password
    public function showForgotForm()
    {
        return view('auth.guru-forgot');
    }

    // Kirim Link Reset Password ke Email
    public function sendResetLink(Request $request)
    {
        // Validasi email harus ada di tabel users
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar.',
        ]);

        // Helper Password::broker() menangani pengiriman token reset link standar Laravel
        $status = Password::broker()->sendResetLink(
            $request->only('email')
        );

        // Cek hasil pengiriman
        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => 'Link reset password telah dikirim ke email Anda.'])
            : back()->withErrors(['email' => 'Gagal mengirim link reset password.']);
    }

    // Form Reset Password Baru (setelah klik link di email)
    public function showResetForm($token)
    {
        return view('auth.guru-reset', ['token' => $token]);
    }

    // Proses Simpan Password Baru
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed', // confirmed: harus sama dengan field password_confirmation
        ]);

        // Proses reset password oleh Password Broker Laravel
        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Callback jika token valid: update password user
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->setRememberToken(str()->random(60));

                $user->save();
            }
        );

        // Redirect sesuai status hasil reset
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('guru.login')->with('status', 'Password berhasil direset.')
            : back()->withErrors(['email' => [trans($status)]]);
    }
}
