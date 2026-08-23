<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     * Only allows users with the 'customer' role.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Allow super_admin or customer
        if ($user->role === 'customer' || $user->role === 'super_admin') {
            return $next($request);
        }

        // Redirect other internal roles to their respective dashboards
        return match ($user->role) {
            'owner', 'management' => redirect()->route('management.dashboard'),
            'admin'               => redirect()->route('admin.dashboard'),
            'manager'             => redirect()->route('manager.dashboard'),
            'production_officer'  => redirect()->route('manager.production-planning.index'),
            'staff'               => redirect()->route('staff.dashboard'),
            'designer'            => redirect()->route('staff.print-requests.index'),
            'production'          => redirect()->route('production.dashboard'),
            'inventory'           => redirect()->route('inventory.dashboard'),
            default               => redirect()->route('staff.dashboard'),
        };
    }
}
