<?php

use App\Http\Controllers\Tenant\LoginController;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Support\Facades\URL;
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
    $tenant = Tenant::create([
        'name' => ucfirst($domain).' Labs',
        'email' => $domain.'@example.com',
    ]);
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

    $this->get(rbacTenantBaseUrl($tenant).'/settings/roles')->assertOk()->assertSee('Roles');
    $this->get(rbacTenantBaseUrl($tenant).'/settings/roles/create')->assertOk();
    $this->get(rbacTenantBaseUrl($tenant).'/settings/members')->assertOk()->assertSee('Team Members');
    $this->get(rbacTenantBaseUrl($tenant).'/activity')->assertOk();
});

it('denies permission-gated screens to members without roles', function () {
    [$tenant] = rbacTenantWithAdmin('acme');

    $member = User::factory()->create([
        'name' => 'Plain Member',
        'tenant_id' => $tenant->getTenantKey(),
    ]);

    $this->get(rbacLoginUrl($tenant, $member))->assertRedirect();

    $this->get(rbacTenantBaseUrl($tenant).'/settings/roles')->assertForbidden();
    $this->get(rbacTenantBaseUrl($tenant).'/settings/members')->assertForbidden();
    $this->get(rbacTenantBaseUrl($tenant).'/activity')->assertForbidden();
});

it('creates and updates roles for the current tenant', function () {
    [$tenant, $user] = rbacTenantWithAdmin('acme');

    $this->get(rbacLoginUrl($tenant, $user))->assertRedirect();

    $this->post(rbacTenantBaseUrl($tenant).'/settings/roles', [
        'name' => 'Manager',
        'permissions' => ['roles.view', 'users.view'],
    ])->assertRedirect();

    $this->assertDatabaseHas('roles', [
        'name' => 'Manager',
        'guard_name' => config('auth.defaults.guard'),
        'tenant_id' => $tenant->getTenantKey(),
    ]);

    $manager = usingTenantPermissionContext($tenant, fn () => Role::findByName('Manager'));

    $this->get(rbacTenantBaseUrl($tenant).'/settings/roles/'.$manager->getKey().'/edit')->assertOk();

    $this->put(rbacTenantBaseUrl($tenant).'/settings/roles/'.$manager->getKey(), [
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

    $this->get(rbacTenantBaseUrl($tenant).'/settings/roles/'.$adminRole->getKey().'/edit')->assertForbidden();
    $this->put(rbacTenantBaseUrl($tenant).'/settings/roles/'.$adminRole->getKey(), [
        'name' => 'Super Admin',
        'permissions' => ['roles.view'],
    ])->assertForbidden();
    $this->delete(rbacTenantBaseUrl($tenant).'/settings/roles/'.$adminRole->getKey())->assertForbidden();

    $this->get(rbacTenantBaseUrl($tenant).'/settings/roles/'.$otherRole->getKey().'/edit')->assertNotFound();
});

it('manages members and preserves the last admin', function () {
    [$tenant, $user] = rbacTenantWithAdmin('acme');

    $editor = usingTenantPermissionContext($tenant, function () {
        $role = Role::create(['name' => 'Editor']);
        $role->syncPermissions(['users.view']);

        return $role;
    });

    $this->get(rbacLoginUrl($tenant, $user))->assertRedirect();

    $this->post(rbacTenantBaseUrl($tenant).'/settings/members', [
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

    $this->get(rbacTenantBaseUrl($tenant).'/settings/members/'.$member->id.'/edit')->assertOk();

    $this->put(rbacTenantBaseUrl($tenant).'/settings/members/'.$member->id, [
        'name' => 'Grace Murray',
        'email' => 'grace@example.com',
        'roles' => [$editor->getKey()],
    ])->assertRedirect();

    expect($member->fresh()->name)->toBe('Grace Murray');

    $this->delete(rbacTenantBaseUrl($tenant).'/settings/members/'.$user->id)->assertForbidden();

    $this->from(rbacTenantBaseUrl($tenant).'/settings/members/'.$user->id.'/edit')
        ->put(rbacTenantBaseUrl($tenant).'/settings/members/'.$user->id, [
            'name' => $user->name,
            'email' => $user->email,
            'roles' => [],
        ])
        ->assertSessionHasErrors('roles');

    $this->delete(rbacTenantBaseUrl($tenant).'/settings/members/'.$member->id)->assertRedirect();

    expect(User::query()->whereKey($member->id)->exists())->toBeFalse();
});

it('requires a matching password confirmation when adding a member', function () {
    [$tenant, $user] = rbacTenantWithAdmin('acme');

    $this->get(rbacLoginUrl($tenant, $user))->assertRedirect();

    $this->get(rbacTenantBaseUrl($tenant).'/settings/members/create')
        ->assertOk()
        ->assertSee('name="password_confirmation"', false);

    $this->post(rbacTenantBaseUrl($tenant).'/settings/members', [
        'name' => 'Grace Hopper',
        'email' => 'grace@example.com',
        'password' => 'password',
        'password_confirmation' => 'different-password',
        'roles' => [],
    ])->assertSessionHasErrors('password');
});

it('keeps role permissions isolated between tenants', function () {
    [$acme, $acmeUser] = rbacTenantWithAdmin('acme');
    [$globex, $globexUser] = rbacTenantWithAdmin('globex');

    usingTenantPermissionContext($globex, function (): void {
        Role::findByName(PermissionSeeder::ADMIN_ROLE)->syncPermissions(['roles.view']);
    });

    $this->get(rbacLoginUrl($acme, $acmeUser))->assertRedirect();
    $this->get(rbacTenantBaseUrl($acme).'/settings/roles/create')->assertOk();

    $this->get(rbacLoginUrl($globex, $globexUser))->assertRedirect();
    $this->get(rbacTenantBaseUrl($globex).'/settings/roles/create')->assertForbidden();
});

it('logs out from the tenant domain without a CSRF mismatch', function () {
    [$tenant, $user] = rbacTenantWithAdmin('acme');

    $this->get(rbacLoginUrl($tenant, $user))->assertRedirect();

    expect(route('logout'))->toBe(rbacTenantBaseUrl($tenant).'/logout');

    $logoutUrl = URL::temporarySignedRoute('logout', now()->addMinutes(5));
    $logoutResponse = $this->post($logoutUrl);

    $logoutResponse->assertRedirect();
    $this->assertGuest();

    $handoffUrl = $logoutResponse->headers->get('Location');

    expect($handoffUrl)->toContain('logout/handoff')->toContain('signature=');

    $this->get($handoffUrl)->assertRedirect(route('login'));
    $this->assertGuest();
});

it('shows a permission-aware settings navigation and highlights the active section', function () {
    [$tenant, $user] = rbacTenantWithAdmin('acme');

    $this->get(rbacLoginUrl($tenant, $user))->assertRedirect();

    expect(route('settings.workspace.edit'))->toBe(rbacTenantBaseUrl($tenant).'/settings/workspace');
    expect(route('settings.roles.index'))->toBe(rbacTenantBaseUrl($tenant).'/settings/roles');
    expect(route('settings.members.index'))->toBe(rbacTenantBaseUrl($tenant).'/settings/members');
    expect(route('profile.edit'))->toBe(rbacTenantBaseUrl($tenant).'/profile');

    $this->get(route('profile.edit'))
        ->assertOk()
        ->assertSee(rbacTenantBaseUrl($tenant).'/settings/members')
        ->assertSee(rbacTenantBaseUrl($tenant).'/settings/roles')
        ->assertSee(rbacTenantBaseUrl($tenant).'/password')
        ->assertSee('aria-controls="settings-submenu-rail"', false)
        ->assertSee('aria-current="page"', false);

    $this->get(rbacTenantBaseUrl($tenant).'/settings/members')
        ->assertOk()
        ->assertSee(rbacTenantBaseUrl($tenant).'/settings/workspace')
        ->assertSee(rbacTenantBaseUrl($tenant).'/settings/members')
        ->assertSee(rbacTenantBaseUrl($tenant).'/settings/roles')
        ->assertSee(route('profile.edit'))
        ->assertSee('aria-current="page"', false);

    $viewerRole = usingTenantPermissionContext($tenant, function () {
        $role = Role::create(['name' => 'Viewer']);
        $role->syncPermissions(['users.view']);

        return $role;
    });

    $viewer = User::factory()->create([
        'name' => 'View Only',
        'tenant_id' => $tenant->getTenantKey(),
    ]);

    usingTenantPermissionContext($tenant, fn () => $viewer->assignRole($viewerRole));

    $this->get(rbacLoginUrl($tenant, $viewer))->assertRedirect();

    $this->get(rbacTenantBaseUrl($tenant).'/settings/members')
        ->assertOk()
        ->assertSee(rbacTenantBaseUrl($tenant).'/settings/members')
        ->assertDontSee(rbacTenantBaseUrl($tenant).'/settings/workspace')
        ->assertDontSee(rbacTenantBaseUrl($tenant).'/settings/roles');
});

it('renders member details and disables unsafe removal controls', function () {
    [$tenant, $user] = rbacTenantWithAdmin('acme');

    $user->forceFill(['created_at' => '2026-01-15 10:00:00'])->save();

    $editor = usingTenantPermissionContext($tenant, function () {
        $role = Role::create(['name' => 'Editor']);
        $role->syncPermissions(['users.view']);

        return $role;
    });

    $member = User::factory()->create([
        'name' => 'Grace Hopper',
        'email' => 'grace@example.com',
        'tenant_id' => $tenant->getTenantKey(),
        'created_at' => '2026-01-15 10:00:00',
    ]);

    usingTenantPermissionContext($tenant, fn () => $member->assignRole($editor));

    $this->get(rbacLoginUrl($tenant, $user))->assertRedirect();

    $this->get(rbacTenantBaseUrl($tenant).'/settings/members')
        ->assertOk()
        ->assertSee('AO')
        ->assertSee('GH')
        ->assertSee('grace@example.com')
        ->assertSee('Editor')
        ->assertSee('Jan 15, 2026')
        ->assertSee('You cannot delete your own account.', false)
        ->assertSee('The workspace must keep at least one Admin.', false);
});

it('renders a dynamic permission matrix with select all controls', function () {
    [$tenant, $user] = rbacTenantWithAdmin('acme');

    usingTenantPermissionContext($tenant, function (): void {
        Permission::create([
            'name' => 'billing.view',
            'guard_name' => config('auth.defaults.guard'),
        ]);
    });

    $this->get(rbacLoginUrl($tenant, $user))->assertRedirect();

    $this->get(rbacTenantBaseUrl($tenant).'/settings/roles/create')
        ->assertOk()
        ->assertSee('Activity Logs')
        ->assertSee('Roles')
        ->assertSee('Users')
        ->assertSee('Billing')
        ->assertSee('Select All')
        ->assertSee('data-toggle-all-permissions', false);

    $this->post(rbacTenantBaseUrl($tenant).'/settings/roles', [
        'name' => 'Billing Manager',
        'permissions' => ['billing.view'],
    ])->assertRedirect();

    $this->assertDatabaseHas('roles', [
        'name' => 'Billing Manager',
        'tenant_id' => $tenant->getTenantKey(),
    ]);
});
