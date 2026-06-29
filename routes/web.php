<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini adalah tempat mendaftarkan semua route web aplikasi.
| Semua route ini akan menggunakan middleware 'web'.
|
*/

// Halaman Utama (Welcome)
Route::get('/', function () {
    return view('welcome');
});



// --- Rute Login/Logout Siswa ---
Route::get('/siswa/login', [App\Http\Controllers\Auth\SiswaLoginController::class, 'showLoginForm'])->name('siswa.login');
Route::post('/siswa/login', [App\Http\Controllers\Auth\SiswaLoginController::class, 'login'])->name('siswa.login.post');
Route::post('/siswa/logout', [App\Http\Controllers\Auth\SiswaLoginController::class, 'logout'])->name('siswa.logout');

// --- Rute Login/Logout Guru ---
Route::get('/guru/login', [App\Http\Controllers\Auth\GuruLoginController::class, 'showLoginForm'])->name('guru.login');
Route::post('/guru/login', [App\Http\Controllers\Auth\GuruLoginController::class, 'login'])->name('guru.login.post');
Route::post('/guru/logout', [App\Http\Controllers\Auth\GuruLoginController::class, 'logout'])->name('guru.logout');

// --- Rute Lupa Password Guru ---
Route::get('/guru/forgot', [App\Http\Controllers\Auth\GuruLoginController::class, 'showForgotForm'])->name('guru.forgot');
Route::post('/guru/forgot', [App\Http\Controllers\Auth\GuruLoginController::class, 'sendResetLink'])->name('guru.forgot.post');
Route::get('/guru/reset/{token}', [App\Http\Controllers\Auth\GuruLoginController::class, 'showResetForm'])->name('guru.password.reset');
Route::post('/guru/reset', [App\Http\Controllers\Auth\GuruLoginController::class, 'resetPassword'])->name('guru.password.update');

// Rute Logout khusus untuk Panel Filament Guru & Admin
Route::post('/guru/logout-filament', [App\Http\Controllers\Auth\GuruLoginController::class, 'logout'])->name('filament.guru.auth.logout');
Route::post('/admin/logout-filament', [App\Http\Controllers\Auth\AdminLoginController::class, 'logout'])->name('filament.admin.auth.logout');

// --- Rute Login/Logout Admin ---
Route::get('/admin/login', [App\Http\Controllers\Auth\AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [App\Http\Controllers\Auth\AdminLoginController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [App\Http\Controllers\Auth\AdminLoginController::class, 'logout'])->name('admin.logout');

// --- Rute Terproteksi Siswa (Harus Login) ---
Route::middleware(['auth'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\SiswaController::class, 'dashboard'])->name('dashboard');
    Route::get('/ujian', [App\Http\Controllers\SiswaController::class, 'daftarUjian'])->name('ujian');
    Route::get('/ujian/{id}', [App\Http\Controllers\SiswaController::class, 'halamanUjian'])->name('ujian.show');
    Route::post('/ujian/save-answer', [App\Http\Controllers\SiswaController::class, 'saveAnswer'])->name('ujian.save-answer');
    Route::post('/ujian/{id}/submit', [App\Http\Controllers\SiswaController::class, 'submitUjian'])->name('ujian.submit');
    Route::get('/nilai', [App\Http\Controllers\SiswaController::class, 'nilaiUjian'])->name('nilai');
    Route::get('/hasil', [App\Http\Controllers\SiswaController::class, 'hasilUjian'])->name('hasil');
    Route::get('/hasil/{id}/pdf', [App\Http\Controllers\SiswaController::class, 'hasilPdf'])->name('hasil.pdf');
    Route::get('/pengumuman', [App\Http\Controllers\SiswaController::class, 'pengumuman'])->name('pengumuman');
    Route::get('/profile', [App\Http\Controllers\SiswaController::class, 'profile'])->name('profile');
    Route::post('/profile/update-phone', [App\Http\Controllers\SiswaController::class, 'updatePhone'])->name('profile.update-phone');
});

// --- Rute Laporan PDF (Bisa diakses Guru setelah login) ---
Route::get('/laporan/{ujian}/pdf', [App\Http\Controllers\LaporanUjianController::class, 'downloadPdf'])->middleware(['auth'])->name('laporan.pdf');
Route::get('/guru/hasil/{id}/pdf', [App\Http\Controllers\LaporanUjianController::class, 'hasilSiswaPdf'])->middleware(['auth'])->name('guru.hasil.pdf');
