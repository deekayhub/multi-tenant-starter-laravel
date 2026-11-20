<?php

use App\Models\Tenant;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertOk();
});

test('new users can register a tenant with a subdomain', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'tenant_name' => 'Test Tenant',
        'subdomain' => 'test-tenant',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $tenant = Tenant::query()->where('name', 'Test Tenant')->firstOrFail();

    $response->assertRedirectContains('/login/');

    expect(parse_url($response->headers->get('Location'), PHP_URL_HOST))->toBe('test-tenant.saas.test');

    $this->assertAuthenticated();
    $this->assertDatabaseHas('domains', ['domain' => 'test-tenant', 'tenant_id' => $tenant->id]);
    $this->assertDatabaseHas('users', ['email' => 'test@example.com', 'tenant_id' => $tenant->id]);
});
