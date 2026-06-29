<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

// AdminPanelProvider: Mengatur konfigurasi Panel Admin (domain /admin)
class AdminPanelProvider extends PanelProvider
{
    public function boot(): void
    {
        // Matikan proteksi mass assignment untuk kemudahan pengembangan
        Model::unguard();
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin') // ID Panel
            ->path('admin') // URL Path (akses via website.com/admin)
            ->brandName('Admin Panel')
            ->authGuard('web')
            ->colors([
                'primary' => Color::Amber, // Tema warna oranye (Amber)
            ])
            ->pages([
                Pages\Dashboard::class,
            ])
            // Deteksi otomatis Resource, Page, dan Widget di folder Admin
            ->discoverResources(
                in: app_path('Filament/Admin/Resources'),
                for: 'App\\Filament\\Admin\\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/Admin/Pages'),
                for: 'App\\Filament\\Admin\\Pages'
            )
            ->discoverWidgets(
                in: app_path('Filament/Admin/Widgets'),
                for: 'App\\Filament\\Admin\\Widgets'
            )
            ->middleware([
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
                \App\Http\Middleware\FilamentAdminAuthenticate::class,
            ]);
    }
}
