<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the default or customer login form.
     */
    public function showLoginForm()
    {
        return $this->showCustomerLoginForm();
    }

    /**
     * Show the dedicated customer login form.
     */
    public function showCustomerLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectAfterLogin(Auth::user());
        }

        return view('auth.login', ['portal' => 'customer']);
    }

    /**
     * Show the dedicated staff / employee login form.
     */
    public function showStaffLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectAfterLogin(Auth::user());
        }

        return view('auth.login', ['portal' => 'staff']);
    }

    /**
     * Handle an authentication attempt with role-based portal enforcement.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $portalType = $request->input('portal_type', 'customer');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            // 1. On Customer Portal / Main Landing Page: ONLY customer can log in
            if ($portalType === 'customer') {
                if ($user->role !== 'customer') {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    throw ValidationException::withMessages([
                        'email' => __('Access Denied: This login is exclusively for customers. Operations staff must sign in via the Staff Portal.'),
                    ]);
                }
            }

            // 2. On Staff Portal: ONLY employees / operations staff can log in
            if ($portalType === 'staff') {
                if ($user->role === 'customer') {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    throw ValidationException::withMessages([
                        'email' => __('Access Denied: This portal is for authorized operations personnel only. Customers please sign in via the Customer Portal.'),
                    ]);
                }
            }

            $request->session()->regenerate();
            return $this->redirectAfterLogin($user);
        }

        throw ValidationException::withMessages([
            'email' => __('These credentials do not match our records.'),
        ]);
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        $isStaff = $user && ($user->isInternal() || $user->role !== 'customer');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($isStaff) {
            return redirect()->route('staff.portal');
        }

        return redirect()->route('landing');
    }

    /**
     * Redirect to appropriate dashboard based on role for all 9 actors.
     */
    private function redirectAfterLogin($user)
    {
        return match ($user->role) {
            'system_admin'       => redirect()->route('admin.dashboard'),
            'owner'              => redirect()->route('management.dashboard'),
            'management'         => redirect()->route('management.dashboard'),
            'admin'              => redirect()->route('admin.dashboard'),
            'manager'            => redirect()->route('manager.dashboard'),
            'production_officer' => redirect()->route('manager.production-planning.index'),
            'staff'              => redirect()->route('staff.dashboard'),
            'designer'           => redirect()->route('designer.dashboard'),
            'production'         => redirect()->route('production.dashboard'),
            'inventory'          => redirect()->route('inventory.dashboard'),
            'customer'           => redirect()->route('customer.dashboard'),
            default              => redirect()->route('customer.dashboard'),
        };
    }
}
