<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

class LogAuthenticationActivity
{
    /**
     * Record successful sign-ins and sign-outs for the activity log.
     */
    public function handle(Login|Logout $event): void
    {
        $user = $event->user;

        if ($user === null) {
            return;
        }

        $isLogin = $event instanceof Login;

        activity('authentication')
            ->causedBy($user)
            ->event($isLogin ? 'login' : 'logout')
            ->withProperties([
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'guard' => $event->guardName,
                'tenant_id' => $user->tenant_id ?? null,
            ])
            ->log($isLogin ? 'Logged in' : 'Logged out');
    }
}
