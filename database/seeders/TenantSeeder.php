<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TenantSeeder extends Seeder
{
    public const TENANT_COUNT = 50;

    /**
     * Seed deterministic demo workspaces, each with a domain and Admin owner.
     */
    public function run(): void
    {
        $password = Hash::make('password');
        $created = 0;
        $skipped = 0;

        DB::transaction(function () use ($password, &$created, &$skipped): void {
            for ($number = 1; $number <= self::TENANT_COUNT; $number++) {
                $subdomain = sprintf('demo-%02d', $number);

                $exists = Tenant::query()
                    ->whereHas('domains', function (Builder $query) use ($subdomain): void {
                        $query->where('domain', $subdomain);
                    })
                    ->exists();

                if ($exists) {
                    $skipped++;

                    continue;
                }

                $name = sprintf('Demo Workspace %02d', $number);
                $ownerEmail = sprintf('owner%02d@demo.test', $number);

                $tenant = Tenant::query()->create([
                    'name' => $name,
                    'email' => $ownerEmail,
                    'status' => Tenant::STATUS_ACTIVE,
                ]);

                $tenant->domains()->create(['domain' => $subdomain]);

                $tenant->run(function () use ($tenant, $name, $ownerEmail, $password): void {
                    (new TenantDatabaseSeeder)->run();

                    $owner = User::query()->create([
                        'name' => $name.' Owner',
                        'email' => $ownerEmail,
                        'password' => $password,
                        'tenant_id' => $tenant->getTenantKey(),
                        'email_verified_at' => now(),
                    ]);

                    $owner->assignRole(Role::findByName(PermissionSeeder::ADMIN_ROLE));
                });

                $created++;
            }
        });

        $this->command?->info(sprintf(
            'Seeded %d tenants (%d already existed).',
            $created,
            $skipped,
        ));
    }
}
