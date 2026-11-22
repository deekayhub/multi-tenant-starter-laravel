<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant
{
    // Single-database tenancy: no HasDatabase trait.
    use HasDomains;

    public const STATUS_ACTIVE = 'active';

    public const STATUS_SUSPENDED = 'suspended';

    /** @var list<string> */
    public const RESERVED_SUBDOMAINS = [
        'admin', 'api', 'app', 'assets', 'cdn', 'ftp', 'help', 'localhost', 'mail', 'static', 'support', 'www',
    ];

    /** @var array<string, mixed> */
    protected $attributes = [
        'status' => self::STATUS_ACTIVE,
    ];

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
     * Status is stored in the virtual data column. Existing tenants without
     * an explicit value are treated as active.
     */
    protected function status(): Attribute
    {
        return Attribute::get(function (mixed $value): string {
            return $value === self::STATUS_SUSPENDED ? self::STATUS_SUSPENDED : self::STATUS_ACTIVE;
        });
    }

    /**
     * Restrict the query to active tenants.
     */
    #[Scope]
    protected function active(Builder $query): Builder
    {
        return $query->where(function (Builder $query): void {
            $query->where('data->status', self::STATUS_ACTIVE)
                ->orWhereNull('data->status');
        });
    }

    /**
     * Restrict the query to suspended tenants.
     */
    #[Scope]
    protected function suspended(Builder $query): Builder
    {
        return $query->where('data->status', self::STATUS_SUSPENDED);
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
