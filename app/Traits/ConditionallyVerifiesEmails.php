<?php

namespace App\Traits;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

trait ConditionallyVerifiesEmails
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verify(Request $request)
    {
        if ($request->route('id') != $request->user()->getKey()) {
            throw new AuthorizationException;
        }

        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect($this->redirectPath());
    }

    /**
     * Check if user requires email verification
     */
    public function requiresEmailVerification()
    {
        // Customer yang mendaftar sendiri wajib verifikasi
        if ($this->role === 'customer' && $this->registered_by === 'self') {
            return true;
        }

        // Customer yang didaftarkan admin tidak perlu verifikasi
        if ($this->role === 'customer' && $this->registered_by === 'admin') {
            return false;
        }

        // Role lain tidak perlu verifikasi
        return false;
    }
}
