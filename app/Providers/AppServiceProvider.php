<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();
        Model::preventLazyLoading();

        $this->overridePasswordResetUrl();
        $this->overrideEmailVerificationUrl();
    }



    private function overridePasswordResetUrl(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token): string {
            return config('app.frontend_url') . '/reset-password'
                . '?token=' . $token
                . '&email=' . urlencode($notifiable->getEmailForPasswordReset());
        });
    }

    private function overrideEmailVerificationUrl(): void
    {
        VerifyEmail::createUrlUsing(function (object $notifiable): string {
            $verifyUrl = URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );

            return str_replace(
                config('app.url'),
                config('app.frontend_url'),
                $verifyUrl
            );
        });
    }
}
