<?php

use App\Http\Controllers\Tenant\LoginController;
use App\Models\Tenant;
use App\Models\User;
use Database\Seeders\PermissionSeeder;

function centralAdminUser(bool $superAdmin = false, string $email = 'owner@example.com'): User
{
    return User::factory()->create([
        'email' => $email,
        'is_super_admin' => $superAdmin,
    ]);
}

function centralAdminTenant(string $subdomain, string $status = Tenant::STATUS_ACTIVE): Tenant
{
    $tenant = Tenant::query()->create([
        'name' => ucfirst($subdomain).' Labs',
        'email' => $subdomain.'@example.com',
        'status' => $status,
    ]);

    $tenant->domains()->create(['domain' => $subdomain]);

    return $tenant;
}

function centralAdminLoginUrl(Tenant $tenant, User $user): string
{
    return 'http://'.$tenant->preferredHost().'/login/'.LoginController::issueToken($user, $tenant);
}

function centralAdminOwner(Tenant $tenant, string $email): User
{
    $owner = User::factory()->create([
        'name' => ucfirst($tenant->subdomain() ?? 'Workspace').' Owner',
        'email' => $email,
        'tenant_id' => $tenant->getTenantKey(),
    ]);

    assignTenantAdminRole($owner, $tenant);

    return $owner;
}

it('redirects super admins to the central dashboard after login', function () {
    centralAdminUser(true, 'admin@example.com');
    $tenant = centralAdminTenant('acme');

    $this->post(route('login'), [
        'email' => 'admin@example.com',
        'password' => 'password',
    ])->assertRedirect(route('admin.dashboard'));

    $this->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee($tenant->name);
});

it('renders every central admin page for a super admin', function () {
    $this->actingAs(centralAdminUser(true, 'admin@example.com'));

    $tenant = centralAdminTenant('acme');

    $this->get(route('admin.tenants.index'))
        ->assertOk()
        ->assertSee($tenant->name);

    $this->get(route('admin.tenants.show', $tenant))
        ->assertOk()
        ->assertSee($tenant->name);

    $this->get(route('admin.activity-logs'))->assertOk();
});

it('blocks central users who are not super admins', function () {
    $this->actingAs(centralAdminUser())
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

it('lets a super admin provision a tenant with an Admin owner', function () {
    $this->actingAs(centralAdminUser(true, 'admin@example.com'));

    $this->get(route('admin.tenants.create'))->assertOk();

    $this->post(route('admin.tenants.store'), [
        'workspace_name' => 'Northwind Labs',
        'subdomain' => 'northwind',
        'owner_name' => 'Grace Hopper',
        'owner_email' => 'grace@northwind.test',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertRedirect();

    $tenant = Tenant::query()->where('name', 'Northwind Labs')->firstOrFail();
    $owner = User::query()->where('email', 'grace@northwind.test')->firstOrFail();

    expect($tenant->status)->toBe(Tenant::STATUS_ACTIVE)
        ->and($tenant->domains()->pluck('domain')->all())->toContain('northwind')
        ->and((string) $owner->tenant_id)->toBe($tenant->getTenantKey());

    $roleNames = usingTenantPermissionContext(
        $tenant,
        fn (): array => $owner->fresh()->roles->pluck('name')->all(),
    );

    expect($roleNames)->toContain(PermissionSeeder::ADMIN_ROLE);

    $this->assertDatabaseHas('activity_log', [
        'log_name' => 'admin',
        'event' => 'tenant.created',
    ]);
});

it('lets a super admin suspend, reactivate, and delete a tenant', function () {
    $this->actingAs(centralAdminUser(true, 'admin@example.com'));

    $tenant = centralAdminTenant('acme');
    $owner = centralAdminOwner($tenant, 'acme-owner@example.com');

    $this->patch(route('admin.tenants.toggle-status', $tenant))->assertRedirect();

    expect($tenant->fresh()->status)->toBe(Tenant::STATUS_SUSPENDED);

    $this->get('http://acme.saas.test')
        ->assertForbidden()
        ->assertSee('Workspace Suspended');

    $this->patch(route('admin.tenants.toggle-status', $tenant))->assertRedirect();

    expect($tenant->fresh()->status)->toBe(Tenant::STATUS_ACTIVE);

    $this->get('http://acme.saas.test')->assertOk();

    $this->delete(route('admin.tenants.destroy', $tenant))
        ->assertRedirect(route('admin.tenants.index'));

    $this->assertDatabaseMissing('tenants', ['id' => $tenant->getTenantKey()])
        ->assertDatabaseMissing('domains', ['domain' => 'acme'])
        ->assertDatabaseMissing('users', ['id' => $owner->getKey()]);

    $this->assertDatabaseHas('activity_log', [
        'log_name' => 'admin',
        'event' => 'tenant.deleted',
    ]);
});

it('blocks a suspended tenant from all tenant routes', function () {
    $tenant = centralAdminTenant('suspended', Tenant::STATUS_SUSPENDED);
    $owner = centralAdminOwner($tenant, 'suspended-owner@example.com');

    $this->get(centralAdminLoginUrl($tenant, $owner))
        ->assertForbidden()
        ->assertSee('Workspace Suspended');

    $this->get('http://suspended.saas.test/settings/roles')
        ->assertForbidden()
        ->assertSee('Workspace Suspended');
});

it('lets a super admin impersonate a tenant Admin', function () {
    $this->actingAs(centralAdminUser(true, 'admin@example.com'));

    $tenant = centralAdminTenant('acme');
    $owner = centralAdminOwner($tenant, 'acme-owner@example.com');

    $response = $this->get(route('admin.tenants.impersonate', $tenant))->assertRedirect();

    $impersonationUrl = $response->headers->get('Location');

    expect($impersonationUrl)
        ->toContain('acme.saas.test')
        ->toContain('/impersonate/');

    $this->get($impersonationUrl)
        ->assertRedirect()
        ->assertLocation(tenant_route('acme.saas.test', 'tenant.dashboard'));

    $this->assertAuthenticatedAs($owner);

    $this->assertDatabaseCount('tenant_user_impersonation_tokens', 0);
});

it('shows the central activity log to super admins', function () {
    $this->actingAs(centralAdminUser(true, 'admin@example.com'));

    $tenant = centralAdminTenant('acme');

    $this->patch(route('admin.tenants.toggle-status', $tenant))->assertRedirect();

    $this->get(route('admin.activity-logs'))
        ->assertOk()
        ->assertSee('Activity Logs')
        ->assertSee('tenant.status_updated');
});
