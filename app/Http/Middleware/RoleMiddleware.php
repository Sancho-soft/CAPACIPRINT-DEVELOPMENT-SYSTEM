<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request with role hierarchy support.
     * Usage: Route::middleware(['auth', 'role:staff,manager'])
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role;

        // Super Admin has universal access
        if ($userRole === 'super_admin') {
            return $next($request);
        }

        // Owner maps to management
        if ($userRole === 'owner' && in_array('management', $roles)) {
            return $next($request);
        }

        // Production Officer maps to manager
        if ($userRole === 'production_officer' && (in_array('manager', $roles) || in_array('production', $roles))) {
            return $next($request);
        }

        // Layout Designer maps to staff
        if ($userRole === 'designer' && (in_array('staff', $roles) || in_array('production', $roles))) {
            return $next($request);
        }

        if (! in_array($userRole, $roles)) {
            abort(403, 'You do not have permission to access this area.');
        }

        return $next($request);
    }
}
