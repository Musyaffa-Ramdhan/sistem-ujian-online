<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Controller untuk login khusus Siswa (tanpa Email/Password biasa)
class SiswaLoginController extends Controller
{
    // Tampilkan form login siswa
    public function showLoginForm()
    {
        return view('auth.siswa-login');
    }

    // Proses data login
    public function login(Request $request)
    {
        // Validasi input: Nama harus string, NISN harus 10 karakter
        $request->validate([
            'nama' => 'required|string',
            'nisn' => 'required|string|size:10',
        ], [
            'nama.required' => 'Nama siswa wajib diisi.',
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.size' => 'NISN harus 10 karakter.',
        ]);

        // Cari data siswa di database berdasarkan nama dan nisn yang persis sama
        $siswa = Siswa::where('nama', $request->nama)
            ->where('nisn', $request->nisn)
            ->first();

        // Jika siswa ditemukan DAN siswa memiliki akun user yang terhubung
        if ($siswa && $siswa->user) {
            // Login manual menggunakan Facade Auth
            // Auth::login($user) meloginkan instance user tersebut
            Auth::login($siswa->user);

            // Redirect ke dashboard siswa (intended menghandle redirect back jika user akses halaman terproteksi sebelumnya)
            return redirect()->intended(route('siswa.dashboard'));
        }

        // Jika gagal login
        // back() kembalikan ke halaman login
        // withErrors() kirim pesan error untuk ditampilkan di form
        // withInput() kembalikan input user agar tidak perlu ketik ulang (kecuali password/sensitif)
        return back()->withErrors([
            'nama' => 'Nama atau NISN tidak sesuai',
        ])->withInput($request->only('nama'));
    }

    // Proses Logout
    public function logout(Request $request)
    {
        Auth::logout(); // Logout dari sesi aplikasi
        $request->session()->invalidate(); // Hapus sesi saat ini utk keamanan
        $request->session()->regenerateToken(); // Regenerasi token CSRF

        return redirect()->route('siswa.login');
    }
}
