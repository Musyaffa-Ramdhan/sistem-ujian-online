<?php

namespace App\Notifications;

// Meng-extend notifikasi reset password bawaan Laravel
use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;

// GuruResetPasswordNotification: Kustomisasi notifikasi email lupa password khusus Guru
class GuruResetPasswordNotification extends BaseResetPassword
{
    public function __construct($token)
    {
        // Menyimpan token reset yang dikirim Laravel
        parent::__construct($token);
    }

    /**
     * resetUrl: Menentukan arah link 'Reset Password' yang ada di email
     */
    protected function resetUrl($notifiable)
    {
        // Diarahkan ke route 'guru.password.reset' (halaman form password baru untuk guru)
        return route('guru.password.reset', ['token' => $this->token]);
    }
}