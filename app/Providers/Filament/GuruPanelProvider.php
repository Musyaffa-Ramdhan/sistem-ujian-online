<?php

namespace App\Providers\Filament;

// Import Middleware dan Class Filament
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

// GuruPanelProvider: Mengatur konfigurasi Panel Guru (domain /guru)
class GuruPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('guru') // ID Panel
            ->path('guru') // URL Path (akses via website.com/guru)
            ->brandName('Guru Panel') // Nama brand di header
            ->colors([
                'primary' => \Filament\Support\Colors\Color::Emerald, // Tema warna hijau (Emerald)
            ])
            ->default(false)
            ->authGuard('web') // Menggunakan guard default Laravel (web)
            ->registration(false) // Nonaktifkan daftar mandiri
            
            ->pages([
                // Daftar halaman statis di panel
                \App\Filament\Guru\Pages\Dashboard::class,
            ])

            ->discoverResources(
                // Otomatis mencari file Resource di folder app/Filament/Guru/Resources
                in: app_path('Filament/Guru/Resources'),
                for: 'App\\Filament\\Guru\\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/Guru/Pages'),
                for: 'App\\Filament\\Guru\\Pages'
            )
            ->discoverWidgets(
                in: app_path('Filament/Guru/Widgets'),
                for: 'App\\Filament\\Guru\\Widgets'
            )

            ->middleware([
                // Middleware standar Laravel dan Filament
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])

            ->authMiddleware([
                // Middleware untuk memastikan user sudah login sebelum masuk panel
                \App\Http\Middleware\FilamentGuruAuthenticate::class,
            ]);
    }
}
