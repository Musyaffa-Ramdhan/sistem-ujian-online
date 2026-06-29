<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Only check role if user is authenticated
        // If not authenticated, Filament's Authenticate middleware will handle redirect to login
        if (auth()->check()) {
            $user = auth()->user();
            if (! $user->hasRole($role)) {
                abort(403, 'Unauthorized access. You do not have permission to access this panel.');
            }
        }

        return $next($request);
    }
}
