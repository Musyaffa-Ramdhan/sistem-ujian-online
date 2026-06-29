<?php

namespace App\Filament\Auth;

use App\Models\User;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CustomLogin extends BaseLogin
{
    public function authenticate(): ?LoginResponse
    {
        try {
            return parent::authenticate();
        } catch (ValidationException $e) {
            // Intercept standard failure and checking manually for granular message
            $data = $this->form->getState();

            $user = User::where('email', $data['email'])->first();

            if (! $user) {
                throw ValidationException::withMessages([
                    'data.email' => __('Email tidak terdaftar.'),
                ]);
            }

            // If user exists but authentication failed, it must be password
            // (Assuming parent logic failed due to credentials)

            // We can double check password match explicitly to be sure
            if (! Hash::check($data['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'data.password' => __('Password yang Anda masukkan salah.'),
                ]);
            }

            // Fallback
            throw $e;
        }
    }
}
