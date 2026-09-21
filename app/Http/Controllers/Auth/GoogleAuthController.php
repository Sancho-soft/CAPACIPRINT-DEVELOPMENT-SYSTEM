<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Automatically redirect the customer directly to Google's official sign-in page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    /**
     * Handle the response when the customer completes sign-in on their Google account.
     * Enforces that Google sign-in is strictly for Customers.
     */
    public function handleGoogleCallback(Request $request)
    {
        if ($request->has('error')) {
            return redirect()->route('login')->withErrors([
                'email' => 'Google sign-in was cancelled or encountered an error.',
            ]);
        }

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Google sign-in error: ' . $e->getMessage(),
            ]);
        }

        if (!$googleUser || !$googleUser->getEmail()) {
            return redirect()->route('login')->withErrors([
                'email' => 'No verified email returned from your Google account.',
            ]);
        }

        $email = strtolower(trim($googleUser->getEmail()));

        // Check if user already exists
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $email)
            ->first();

        // Strict Enforcement: Google is exclusively for Customers
        if ($user && $user->role !== 'customer') {
            return redirect()->route('login')->withErrors([
                'email' => 'Access Denied: Google sign-in is exclusively for customers. Operations staff must sign in via the Staff Portal.',
            ]);
        }

        if ($user) {
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar'    => $googleUser->getAvatar() ?? $user->avatar,
            ]);
        } else {
            // Register new user automatically as a Customer
            $user = User::create([
                'name'              => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Google Customer',
                'email'             => $email,
                'google_id'         => $googleUser->getId(),
                'avatar'            => $googleUser->getAvatar(),
                'role'              => 'customer',
                'password'          => Hash::make(Str::random(32)),
                'email_verified_at' => now(),
            ]);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->route('customer.dashboard');
    }
}
