<?php

use App\Http\Controllers\Tenant\LoginController;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Spatie\Activitylog\Models\Activity;

function rbacTenantBaseUrl(Tenant $tenant): string
{
    return 'http://'.$tenant->domains()->firstOrFail()->domain.'.saas.test';
}

function rbacLoginUrl(Tenant $tenant, User $user): string
{
    return rbacTenantBaseUrl($tenant).'/login/'.LoginController::issueToken($user, $tenant);
}

function rbacTenantWithAdmin(string $domain): array
{
    $tenant = Tenant::create(['name' => ucfirst($domain).' Labs']);
    $tenant->domains()->create(['domain' => $domain]);

    $user = User::factory()->create([
        'name' => ucfirst($domain).' Owner',
        'tenant_id' => $tenant->getTenantKey(),
    ]);

    assignTenantAdminRole($user, $tenant);

    return [$tenant, $user];
}

it('provisions permissions and an admin role when a tenant is created', function () {
    $tenant = Tenant::create(['name' => 'Acme']);

    $role = usingTenantPermissionContext($tenant, fn () => Role::findByName(PermissionSeeder::ADMIN_ROLE));

    $permissionNames = $role->permissions->pluck('name')->sort()->values()->all();

    expect($permissionNames)->toBe(collect(PermissionSeeder::names())->sort()->values()->all());
    expect((string) $role->getAttribute(config('permission.column_names.team_foreign_key')))->toBe($tenant->getTenantKey());
});

it('assigns the admin role to the tenant owner during registration', function () {
    $this->post('/register', [
        'name' => 'Ada Lovelace',
        'tenant_name' => 'Globex Labs',
        'subdomain' => 'globex',
        'email' => 'ada@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertRedirect();

    $tenant = Tenant::query()->where('name', 'Globex Labs')->firstOrFail();
    $user = User::query()->where('email', 'ada@example.com')->firstOrFail();

    $roleNames = usingTenantPermissionContext(
        $tenant,
        fn () => $user->fresh()->roles->pluck('name')->all(),
    );

    expect($roleNames)->toContain(PermissionSeeder::ADMIN_ROLE);
});

it('lets an admin reach the role, member, and activity screens', function () {
    [$tenant, $user] = rbacTenantWithAdmin('acme');

    $this->get(rbacLoginUrl($tenant, $user))->assertRedirect();

    $this->get(rbacTenantBaseUrl($tenant).'/roles')->assertOk()->assertSee('Roles');
    $this->get(rbacTenantBaseUrl($tenant).'/roles/create')->assertOk();
    $this->get(rbacTenantBaseUrl($tenant).'/members')->assertOk()->assertSee('Team Members');
    $this->get(rbacTenantBaseUrl($tenant).'/activity')->assertOk();
});

it('denies permission-gated screens to members without roles', function () {
    [$tenant] = rbacTenantWithAdmin('acme');

    $member = User::factory()->create([
        'name' => 'Plain Member',
        'tenant_id' => $tenant->getTenantKey(),
    ]);

    $this->get(rbacLoginUrl($tenant, $member))->assertRedirect();

    $this->get(rbacTenantBaseUrl($tenant).'/roles')->assertForbidden();
    $this->get(rbacTenantBaseUrl($tenant).'/members')->assertForbidden();
    $this->get(rbacTenantBaseUrl($tenant).'/activity')->assertForbidden();
});

it('creates and updates roles for the current tenant', function () {
    [$tenant, $user] = rbacTenantWithAdmin('acme');

    $this->get(rbacLoginUrl($tenant, $user))->assertRedirect();

    $this->post(rbacTenantBaseUrl($tenant).'/roles', [
        'name' => 'Manager',
        'permissions' => ['roles.view', 'users.view'],
    ])->assertRedirect();

    $this->assertDatabaseHas('roles', [
        'name' => 'Manager',
        'guard_name' => config('auth.defaults.guard'),
        'tenant_id' => $tenant->getTenantKey(),
    ]);

    $manager = usingTenantPermissionContext($tenant, fn () => Role::findByName('Manager'));

    $this->get(rbacTenantBaseUrl($tenant).'/roles/'.$manager->getKey().'/edit')->assertOk();

    $this->put(rbacTenantBaseUrl($tenant).'/roles/'.$manager->getKey(), [
        'name' => 'Team Lead',
        'permissions' => ['users.view'],
    ])->assertRedirect();

    $updatedPermissions = usingTenantPermissionContext(
        $tenant,
        fn () => Role::findByName('Team Lead')->permissions->pluck('name')->all(),
    );

    expect($updatedPermissions)->toBe(['users.view']);

    $roleActivities = Activity::query()
        ->inLog('roles')
        ->where('subject_type', (new Role)->getMorphClass())
        ->where('subject_id', $manager->getKey())
        ->pluck('event')
        ->all();

    expect($roleActivities)->toContain('created', 'permissions_attached', 'updated', 'permissions_updated');
});

it('protects the admin role and hides roles from other tenants', function () {
    [$tenant, $user] = rbacTenantWithAdmin('acme');
    [$otherTenant] = rbacTenantWithAdmin('globex');

    $this->get(rbacLoginUrl($tenant, $user))->assertRedirect();

    $adminRole = usingTenantPermissionContext($tenant, fn () => Role::findByName(PermissionSeeder::ADMIN_ROLE));
    $otherRole = usingTenantPermissionContext($otherTenant, fn () => Role::findByName(PermissionSeeder::ADMIN_ROLE));

    $this->get(rbacTenantBaseUrl($tenant).'/roles/'.$adminRole->getKey().'/edit')->assertForbidden();
    $this->put(rbacTenantBaseUrl($tenant).'/roles/'.$adminRole->getKey(), [
        'name' => 'Super Admin',
        'permissions' => ['roles.view'],
    ])->assertForbidden();
    $this->delete(rbacTenantBaseUrl($tenant).'/roles/'.$adminRole->getKey())->assertForbidden();

    $this->get(rbacTenantBaseUrl($tenant).'/roles/'.$otherRole->getKey().'/edit')->assertNotFound();
});

it('manages members and preserves the last admin', function () {
    [$tenant, $user] = rbacTenantWithAdmin('acme');

    $editor = usingTenantPermissionContext($tenant, function () {
        $role = Role::create(['name' => 'Editor']);
        $role->syncPermissions(['users.view']);

        return $role;
    });

    $this->get(rbacLoginUrl($tenant, $user))->assertRedirect();

    $this->post(rbacTenantBaseUrl($tenant).'/members', [
        'name' => 'Grace Hopper',
        'email' => 'grace@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'roles' => [$editor->getKey()],
    ])->assertRedirect();

    $member = User::query()->where('email', 'grace@example.com')->firstOrFail();

    $this->assertDatabaseHas('model_has_roles', [
        'model_type' => User::class,
        'model_id' => $member->id,
        'role_id' => $editor->getKey(),
        'tenant_id' => $tenant->getTenantKey(),
    ]);

    $this->get(rbacTenantBaseUrl($tenant).'/members/'.$member->id.'/edit')->assertOk();

    $this->put(rbacTenantBaseUrl($tenant).'/members/'.$member->id, [
        'name' => 'Grace Murray',
        'email' => 'grace@example.com',
        'roles' => [$editor->getKey()],
    ])->assertRedirect();

    expect($member->fresh()->name)->toBe('Grace Murray');

    $this->delete(rbacTenantBaseUrl($tenant).'/members/'.$user->id)->assertForbidden();

    $this->from(rbacTenantBaseUrl($tenant).'/members/'.$user->id.'/edit')
        ->put(rbacTenantBaseUrl($tenant).'/members/'.$user->id, [
            'name' => $user->name,
            'email' => $user->email,
            'roles' => [],
        ])
        ->assertSessionHasErrors('roles');

    $this->delete(rbacTenantBaseUrl($tenant).'/members/'.$member->id)->assertRedirect();

    expect(User::query()->whereKey($member->id)->exists())->toBeFalse();
});

it('keeps role permissions isolated between tenants', function () {
    [$acme, $acmeUser] = rbacTenantWithAdmin('acme');
    [$globex, $globexUser] = rbacTenantWithAdmin('globex');

    usingTenantPermissionContext($globex, function (): void {
        Role::findByName(PermissionSeeder::ADMIN_ROLE)->syncPermissions(['roles.view']);
    });

    $this->get(rbacLoginUrl($acme, $acmeUser))->assertRedirect();
    $this->get(rbacTenantBaseUrl($acme).'/roles/create')->assertOk();

    $this->get(rbacLoginUrl($globex, $globexUser))->assertRedirect();
    $this->get(rbacTenantBaseUrl($globex).'/roles/create')->assertForbidden();
});
