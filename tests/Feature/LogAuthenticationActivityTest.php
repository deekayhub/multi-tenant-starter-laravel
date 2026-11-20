<?php

use App\Models\User;
use Illuminate\Support\Facades\URL;

it('logs a login event when a user signs in', function () {
    $user = User::factory()->create();

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('dashboard', absolute: false));

    $this->assertDatabaseHas('activity_log', [
        'log_name' => 'authentication',
        'event' => 'login',
        'description' => 'Logged in',
        'causer_type' => $user->getMorphClass(),
        'causer_id' => $user->getKey(),
    ]);
});

it('logs a logout event when a user signs out', function () {
    $user = User::factory()->create();

    $logoutUrl = URL::temporarySignedRoute('central.logout', now()->addMinutes(5));

    $this->actingAs($user)->post($logoutUrl)->assertRedirect('/');

    $this->assertDatabaseHas('activity_log', [
        'log_name' => 'authentication',
        'event' => 'logout',
        'description' => 'Logged out',
        'causer_type' => $user->getMorphClass(),
        'causer_id' => $user->getKey(),
    ]);
});

it('does not log an unsuccessful login attempt', function () {
    $user = User::factory()->create();

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ])->assertSessionHasErrors('email');

    $this->assertDatabaseCount('activity_log', 0);
});
