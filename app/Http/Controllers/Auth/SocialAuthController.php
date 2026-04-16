<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback — login or auto-register
     */
    public function handleGoogleCallback()
    {
        try {
            $socialUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['social' => 'Google authentication failed. Please try again.']);
        }

        return $this->loginOrRegister('google', $socialUser);
    }

    /**
     * Redirect to Facebook OAuth
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Handle Facebook OAuth callback — login or auto-register
     */
    public function handleFacebookCallback()
    {
        try {
            $socialUser = Socialite::driver('facebook')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['social' => 'Facebook authentication failed. Please try again.']);
        }

        return $this->loginOrRegister('facebook', $socialUser);
    }

    /**
     * Unified: find existing user by social ID or email, else create new account.
     */
    private function loginOrRegister(string $provider, $socialUser)
    {
        $idColumn = $provider . '_id';

        // 1. Find by social provider ID
        $user = User::where($idColumn, $socialUser->getId())->first();

        if (! $user && $socialUser->getEmail()) {
            // 2. Find by email (link social to existing account)
            $user = User::where('email', $socialUser->getEmail())->first();
            if ($user) {
                $user->update([
                    $idColumn  => $socialUser->getId(),
                    'avatar'   => $user->avatar ?? $socialUser->getAvatar(),
                ]);
            }
        }

        if (! $user) {
            // 3. Auto-register new user
            $user = User::create([
                'name'      => $socialUser->getName() ?? 'User',
                'email'     => $socialUser->getEmail(),
                $idColumn   => $socialUser->getId(),
                'avatar'    => $socialUser->getAvatar(),
                'password'  => null,
                'email_verified_at' => now(),
            ]);
        }

        // Capture guest session before login for cart migration
        $guestSessionId = request()->session()->getId();

        Auth::login($user, true);

        // Migrate guest cart to user
        app(LoginController::class)->migrateGuestCart($guestSessionId, $user->id);

        request()->session()->regenerate();

        return redirect()->intended(route('home'));
    }
}
