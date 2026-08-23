<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectAfterLogin(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return $this->redirectAfterLogin(Auth::user());
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
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Redirect to appropriate dashboard based on role.
     */
    private function redirectAfterLogin($user)
    {
        return match ($user->role) {
            'superadmin', 'admin', 'sysadmin'            => redirect()->route('admin.dashboard'),
            'management', 'owner'                        => redirect()->route('management.dashboard'),
            'manager', 'production_officer'              => redirect()->route('manager.dashboard'),
            'staff', 'cs'                                => redirect()->route('staff.dashboard'),
            'designer'                                   => redirect()->route('staff.print-requests.index'),
            'production', 'operator'                     => redirect()->route('production.dashboard'),
            'inventory'                                  => redirect()->route('inventory.dashboard'),
            default                                      => redirect()->route('customer.dashboard'),
        };
    }
}
