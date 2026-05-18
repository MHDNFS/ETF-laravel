<?php

use App\Models\User;
use App\UserRole;
use Database\Seeders\SuperAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('rejects api login when email and password are missing', function () {
    $this->postJson('/api/auth/login', [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email', 'password']);
});

it('rejects api login with wrong password', function () {
    $user = User::factory()->create([
        'password' => 'password',
    ]);

    $this->postJson('/api/auth/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

it('returns bearer token from api login', function () {
    $user = User::factory()->create([
        'password' => 'password',
        'role' => UserRole::SuperAdmin,
    ]);

    $response = $this->postJson('/api/auth/login', [
        'email' => $user->email,
        'password' => 'password',
        'device_name' => 'test',
    ]);

    $response
        ->assertOk()
        ->assertJsonStructure(['token', 'token_type', 'user' => ['id', 'email', 'role', 'initials']]);

    expect($response->json('token_type'))->toBe('Bearer');
});

it('returns current user from api me with bearer token', function () {
    $user = User::factory()->create([
        'role' => UserRole::SuperAdmin,
    ]);

    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->getJson('/api/auth/me')
        ->assertOk()
        ->assertJsonPath('user.email', $user->email);
});

it('rejects api me without token', function () {
    $this->getJson('/api/auth/me')->assertUnauthorized();
});

it('logs out and revokes token via api', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    expect($user->tokens()->count())->toBe(1);

    $this->withToken($token)
        ->postJson('/api/auth/logout')
        ->assertOk();

    expect($user->tokens()->count())->toBe(0);

    $this->app['auth']->forgetGuards();

    $this->withToken($token)
        ->getJson('/api/auth/me')
        ->assertUnauthorized();
});

it('seeds super admin account', function () {
    $this->seed(SuperAdminSeeder::class);

    $user = User::query()->where('email', 'superadmin@gmail.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->role)->toBe(UserRole::SuperAdmin)
        ->and($user->isSuperAdmin())->toBeTrue();
});

it('serves login page at root without blade auth directives', function () {
    $html = $this->get('/')->assertOk()->getContent();

    expect($html)
        ->not->toContain('@csrf')
        ->not->toContain('auth()->user()')
        ->toContain('<title>Login Page</title>')
        ->toContain('auth-api.js')
        ->toContain('auth-login.js');
});

it('redirects legacy login url to root', function () {
    $this->get('/login')->assertRedirect('/');
});
