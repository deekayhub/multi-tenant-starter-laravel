<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
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
     * Normalize a hostname submitted through a form.
     */
    public static function normalizeHostname(string $domain): string
    {
        $domain = Str::lower(trim($domain));
        $domain = Str::after($domain, 'https://');
        $domain = Str::after($domain, 'http://');
        $domain = Str::before($domain, '/');
        $domain = Str::before($domain, ':');

        return rtrim($domain, '.');
    }

    /**
     * Resolve the short subdomain label for this tenant, if any.
     */
    public function subdomain(): ?string
    {
        $centralDomain = (string) config('tenancy.central_domains')[0];

        return $this->domainNames()
            ->map(function (string $domain) use ($centralDomain): ?string {
                if (str_ends_with($domain, '.'.$centralDomain)) {
                    return Str::before($domain, '.'.$centralDomain);
                }

                return str_contains($domain, '.') ? null : $domain;
            })
            ->filter()
            ->first();
    }

    /**
     * Resolve the custom hostname for this tenant, if any.
     */
    public function customDomain(): ?string
    {
        $centralDomain = (string) config('tenancy.central_domains')[0];

        return $this->domainNames()->first(
            fn (string $domain): bool => str_contains($domain, '.') && ! str_ends_with($domain, '.'.$centralDomain)
        );
    }

    /**
     * Resolve the hostname a user should land on after authenticating.
     */
    public function preferredHost(): ?string
    {
        $customDomain = $this->customDomain();

        if ($customDomain !== null) {
            return $customDomain;
        }

        $subdomain = $this->subdomain();

        if ($subdomain === null) {
            return null;
        }

        return $subdomain.'.'.config('tenancy.central_domains')[0];
    }

    /** @return Collection<int, string> */
    private function domainNames(): Collection
    {
        return $this->domains()->pluck('domain')
            ->map(fn (mixed $domain): string => (string) $domain)
            ->values();
    }
}
