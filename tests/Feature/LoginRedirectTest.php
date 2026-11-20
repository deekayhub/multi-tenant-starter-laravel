<?php

use App\Models\Tenant;
use App\Models\User;

it('redirects a user to their subdomain after login', function () {
    config(['tenancy.central_domains' => ['saas.test', 'localhost', '127.0.0.1']]);

    $tenant = Tenant::create(['name' => 'Acme']);
    $tenant->domains()->create(['domain' => 'acme']);

    $user = User::factory()->create(['tenant_id' => $tenant->id]);

    $response = $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirectContains('/login/');

    expect(parse_url($response->headers->get('Location'), PHP_URL_HOST))->toBe('acme.saas.test');
});

it('redirects a user to their custom domain after login', function () {
    config(['tenancy.central_domains' => ['saas.test', 'localhost', '127.0.0.1']]);

    $tenant = Tenant::create(['name' => 'Acme']);
    $tenant->domains()->create(['domain' => 'acme']);
    $tenant->domains()->create(['domain' => 'acme.example.com']);

    $user = User::factory()->create(['tenant_id' => $tenant->id]);

    $response = $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirectContains('/login/');

    expect(parse_url($response->headers->get('Location'), PHP_URL_HOST))->toBe('acme.example.com');
});

it('falls back to the dashboard when the user has no tenant', function () {
    $user = User::factory()->create();

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('dashboard', absolute: false));
});
