<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    /**
     * Set the Socialite provider config dynamically from the settings table.
     */
    protected function configureProvider(string $provider): void
    {
        if ($provider === 'google') {
            config([
                'services.google.client_id'     => Setting::get('google_client_id'),
                'services.google.client_secret'  => Setting::get('google_client_secret'),
                'services.google.redirect'       => url('/auth/google/callback'),
            ]);
        }
    }

    public function redirect($provider)
    {
        $this->configureProvider($provider);

        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $this->configureProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)->user();
            
            $user = User::firstOrCreate(
                ['email' => $socialUser->getEmail()],
                [
                    'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                    'password' => bcrypt(Str::random(24)),
                    'email_verified_at' => now(),
                ]
            );

            Auth::login($user);

            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Unable to login using ' . ucfirst($provider)]);
        }
    }
}
