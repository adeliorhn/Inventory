<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return RedirectResponse
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google and log them in.
     *
     * @return RedirectResponse
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Exception $e) {
            return redirect()->route('login')->withErrors([
                'error' => 'Gagal masuk menggunakan Google: ' . $e->getMessage()
            ]);
        }

        // Find or create the user
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        // Get theme from cookie if present and valid
        $cookieTheme = request()->cookie('theme');
        $validTheme = in_array($cookieTheme, ['light', 'dark']) ? $cookieTheme : null;

        if ($user) {
            // Update user details if necessary
            $userTheme = $validTheme ?? ($user->theme ?? 'light');
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar_url' => $googleUser->getAvatar(),
                'theme' => $userTheme,
            ]);
        } else {
            // Create a new user
            $userTheme = $validTheme ?? 'light';
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar_url' => $googleUser->getAvatar(),
                'password' => null,
                'theme' => $userTheme,
            ]);
        }

        // Keep the cookie in sync
        cookie()->queue('theme', $userTheme, 60 * 24 * 365, null, null, false, false);

        Auth::login($user);

        return redirect()->intended(route('dashboard'))
            ->with('status', 'Selamat datang kembali, ' . $user->name . '!');
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Anda telah berhasil keluar.');
    }
}
