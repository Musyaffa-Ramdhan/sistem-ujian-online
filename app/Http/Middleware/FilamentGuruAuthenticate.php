<?php

namespace App\Http\Middleware;

use Filament\Http\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class FilamentGuruAuthenticate extends Middleware
{
    protected function redirectTo($request): ?string
    {
        return route('guru.login');
    }
}
