<?php

use App\Http\Controllers\Tenant\LoginController;
use App\Models\Tenant;
use App\Models\User;

it('redirects guests away from the activity page', function () {
    $this->get(route('activity.index'))
        ->assertRedirect(route('login', absolute: false));
});

it('shows only authentication events from the current group', function () {
    $tenant = Tenant::create(['name' => 'Acme']);
    $otherTenant = Tenant::create(['name' => 'Globex']);

    $user = User::factory()->create(['tenant_id' => $tenant->id, 'name' => 'Alice Example']);
    $otherUser = User::factory()->create(['tenant_id' => $otherTenant->id, 'name' => 'Bob Secret']);

    activity('authentication')->causedBy($user)->event('login')->log('Logged in');
    activity('authentication')->causedBy($otherUser)->event('login')->log('Logged in');

    $this->actingAs($user)->get(route('activity.index'))
        ->assertOk()
        ->assertSee('Alice Example')
        ->assertDontSee('Bob Secret');
});

it('renders the activity page on a tenant domain', function () {
    $tenant = Tenant::create(['name' => 'Acme']);
    $tenant->domains()->create(['domain' => 'acme']);

    $user = User::factory()->create(['tenant_id' => $tenant->id, 'name' => 'Alice Example']);
    assignTenantAdminRole($user, $tenant);

    activity('authentication')->causedBy($user)->event('login')->log('Logged in');

    $token = LoginController::issueToken($user, $tenant);

    $this->get('http://acme.saas.test/login/'.$token)
        ->assertRedirect(route('tenant.dashboard', absolute: false));

    $this->get('http://acme.saas.test/activity')
        ->assertOk()
        ->assertSee('Alice Example');
});

it('filters the activity table by event', function () {
    $user = User::factory()->create(['name' => 'Alice Example']);

    activity('authentication')->causedBy($user)->event('login')->log('Logged in');
    activity('authentication')->causedBy($user)->event('logout')->log('Logged out');

    $this->actingAs($user)->get(route('activity.index', ['event' => 'logout']))
        ->assertOk()
        ->assertSee('Signed out')
        ->assertSee('Alice Example');
});

it('escapes user names in the activity table', function () {
    $user = User::factory()->create(['name' => '<script>alert(1)</script> Alice']);

    activity('authentication')->causedBy($user)->event('login')->log('Logged in');

    $this->actingAs($user)->get(route('activity.index'))
        ->assertOk()
        ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
        ->assertDontSee('<script>alert(1)</script>', false);
});
