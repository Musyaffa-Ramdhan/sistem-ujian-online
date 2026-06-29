<?php

namespace App\Http\Middleware;

use Filament\Http\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class FilamentAdminAuthenticate extends Middleware
{
    protected function redirectTo($request): ?string
    {
        return route('admin.login');
    }
}
