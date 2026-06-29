<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | Bagian ini menentukan default guard dan password reset yang digunakan.
    | Default-nya adalah menggunakan guard 'web' dan provider 'users'.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Guard menentukan bagaimana user diautentikasi (biasanya lewat session).
    | 'web' adalah guard standar untuk aplikasi sistem web biasa.
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | Provider menentukan darimana data user diambil (biasanya tabel 'users').
    | Kita menggunakan driver 'eloquent' dan model 'App\Models\User'.
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | Pengaturan untuk fitur lupa password. Menentukan tabel penyimpanan token,
    | waktu kedaluwarsa token (60 menit), dan batas waktu pengiriman ulang.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Waktu (dalam detik) sebelum konfirmasi password kedaluwarsa (Default: 3 jam).
    |
    */

    'password_timeout' => 10800,

];
