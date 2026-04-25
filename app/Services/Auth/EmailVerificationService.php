<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EmailVerificationService
{
    /**
     * Mark the user's email as verified.
     */
    public function verifyEmail(int $userId, string $hash, Request $request): void
    {
        $user = User::findOrFail($userId);

        if(!hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid verification link.'],
            ]);
        }

        if($user->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'email' => ['Email is already verified.'],
            ]);
        }

        $user->markEmailAsVerified();
        event(new Verified($user));
    }

    /**
     * Resend verification email for an authenticated user.
     */
    public function resendVerificationEmail(User $user): void
    {
        if($user->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'email' => ['Email is already verified.'],
            ]);
        }

        $user->sendEmailVerificationNotification();
    }
}
