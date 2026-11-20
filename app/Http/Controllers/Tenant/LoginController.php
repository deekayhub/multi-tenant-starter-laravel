<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Issue a short-lived, signed handoff token for another domain.
     */
    public static function issueToken(User $user, Tenant $tenant): string
    {
        $payload = implode('|', [
            $tenant->getTenantKey(),
            $user->getKey(),
            now()->addMinutes(15)->timestamp,
        ]);

        return self::encode($payload.'|'.hash_hmac('sha256', $payload, (string) config('app.key')));
    }

    /**
     * Handle an incoming signed handoff token.
     */
    public function __invoke(string $token, Request $request): RedirectResponse
    {
        $decoded = self::decode($token);

        abort_if($decoded === false, 404);

        $parts = explode('|', $decoded);

        abort_if(count($parts) !== 4, 404);

        [$tenantId, $userId, $expiresAt, $signature] = $parts;
        $payload = implode('|', [$tenantId, $userId, $expiresAt]);

        abort_if(! hash_equals(
            hash_hmac('sha256', $payload, (string) config('app.key')),
            $signature,
        ), 404);

        abort_if((int) $expiresAt < now()->timestamp, 404);

        $currentTenant = tenant();

        abort_if($currentTenant === null || (string) $currentTenant->getTenantKey() !== $tenantId, 404);

        $user = User::query()->findOrFail($userId);

        abort_if((string) $user->tenant_id !== $tenantId, 404);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('tenant.dashboard');
    }

    private static function encode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private static function decode(string $value): string|false
    {
        $value = strtr($value, '-_', '+/');
        $value .= str_repeat('=', (4 - strlen($value) % 4) % 4);

        return base64_decode($value, true);
    }
}
