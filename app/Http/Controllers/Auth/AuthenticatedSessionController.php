<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Tenant\LoginController;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();
        $tenant = $user->tenant;
        $host = $tenant?->preferredHost();

        if ($tenant !== null && $host !== null) {
            return redirect(tenant_route($host, 'tenant.login', [
                'token' => LoginController::issueToken($user, $tenant),
            ]));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();
        $tenant = tenant();

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($tenant !== null) {
            $parameters = ['tenant' => $tenant->getTenantKey()];

            if ($user !== null) {
                $parameters['user'] = $user->getKey();
            }

            return redirect()->away(URL::temporarySignedRoute(
                'central.logout.handoff',
                now()->addMinutes(5),
                $parameters,
            ));
        }

        return redirect('/');
    }

    /**
     * Clear the central session after a tenant logout.
     */
    public function handoff(Request $request): RedirectResponse
    {
        if (Auth::check()) {
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
