<?php

namespace App\Models;

use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant
{
    // Single-database tenancy: no HasDatabase trait.
    use HasDomains;

    /**
     * @return list<string>
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'email',
            'data',
        ];
    }

    /**
     * Resolve the hostname a user should land on after authenticating.
     */
    public function preferredHost(): ?string
    {
        $domains = $this->domains()->pluck('domain')
            ->map(fn (mixed $domain): string => (string) $domain);

        $centralDomain = (string) config('tenancy.central_domains')[0];

        $customDomain = $domains->first(
            fn (string $domain): bool => str_contains($domain, '.') && ! str_ends_with($domain, '.'.$centralDomain)
        );

        if ($customDomain !== null) {
            return $customDomain;
        }

        $fullSubdomain = $domains->first(
            fn (string $domain): bool => str_ends_with($domain, '.'.$centralDomain)
        );

        if ($fullSubdomain !== null) {
            return $fullSubdomain;
        }

        $subdomain = $domains->first(fn (string $domain): bool => ! str_contains($domain, '.'));

        return $subdomain !== null ? $subdomain.'.'.$centralDomain : null;
    }
}
