<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Usage: Route::middleware(['auth', 'role:staff,manager'])
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role;

        // Map alias roles to allowed base roles
        $effectiveRoles = match ($userRole) {
            'superadmin', 'sysadmin' => ['admin', 'superadmin', 'sysadmin'],
            'owner'                  => ['management', 'owner'],
            'production_officer'     => ['manager', 'production_officer'],
            'cs'                     => ['staff', 'cs'],
            'designer'               => ['staff', 'designer', 'production'],
            'operator'               => ['production', 'operator'],
            default                  => [$userRole],
        };

        if (! array_intersect($effectiveRoles, $roles)) {
            abort(403, 'You do not have permission to access this area.');
        }

        return $next($request);
    }
}
