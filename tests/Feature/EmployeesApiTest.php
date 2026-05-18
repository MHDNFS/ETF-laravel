<?php

use App\Models\Employee;
use App\Models\User;
use Database\Seeders\EmployeeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('seeds employee records', function () {
    $this->seed(EmployeeSeeder::class);

    expect(Employee::query()->count())->toBe(10);

    $salam = Employee::query()->where('name', 'M M A SALAM')->first();

    expect($salam)
        ->not->toBeNull()
        ->and($salam->nic)->toBe('541682864V')
        ->and($salam->designation)->toBe('Senior Portfolio Manager')
        ->and($salam->bank_account)->toBe('801234567890');
});

it('returns employees from api with bearer token', function () {
    $this->seed(EmployeeSeeder::class);

    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->getJson('/api/employees?per_page=100')
        ->assertOk()
        ->assertJsonCount(10, 'data')
        ->assertJsonPath('data.0.name', 'M M A SALAM')
        ->assertJsonPath('data.0.nic', '541682864V')
        ->assertJsonPath('data.0.designation', 'Senior Portfolio Manager')
        ->assertJsonPath('data.1.designation', 'Fund Operations Executive')
        ->assertJsonPath('meta.has_more', false);
});

it('returns cursor paginated employees', function () {
    $this->seed(EmployeeSeeder::class);

    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $first = $this->withToken($token)
        ->getJson('/api/employees?per_page=5')
        ->assertOk()
        ->assertJsonCount(5, 'data')
        ->assertJsonPath('meta.has_more', true)
        ->assertJsonStructure([
            'data',
            'meta' => ['next_cursor', 'prev_cursor', 'per_page', 'has_more'],
        ]);

    $cursor = $first->json('meta.next_cursor');

    expect($cursor)->not->toBeEmpty();

    $this->withToken($token)
        ->getJson('/api/employees?per_page=5&cursor='.urlencode($cursor))
        ->assertOk()
        ->assertJsonCount(5, 'data')
        ->assertJsonPath('meta.has_more', false);
});

it('filters employees by search term', function () {
    $this->seed(EmployeeSeeder::class);

    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->getJson('/api/employees?search=Habeeb&per_page=100')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Habeeb');
});

it('rejects employees api without token', function () {
    $this->getJson('/api/employees')->assertUnauthorized();
});

it('creates employee via api', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->postJson('/api/employees', [
            'company' => 'ABS',
            'name' => 'Test Employee',
            'nic' => '123456789V',
            'epf_no' => '99',
            'branch' => 'Colombo',
            'designation' => 'Analyst',
            'bank_account' => '123456789012',
            'status' => 'active',
        ])
        ->assertCreated()
        ->assertJsonPath('data.name', 'Test Employee');

    expect(Employee::query()->where('name', 'Test Employee')->exists())->toBeTrue();
});

it('updates employee via api', function () {
    $this->seed(EmployeeSeeder::class);
    $employee = Employee::query()->where('name', 'Habeeb')->first();
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->putJson("/api/employees/{$employee->id}", [
            'company' => 'ABS',
            'name' => 'Habeeb Updated',
            'nic' => '773456789V',
            'epf_no' => '31',
            'branch' => 'Kandy',
            'designation' => 'Compliance Lead',
            'bank_account' => '994567890123',
            'status' => 'active',
        ])
        ->assertOk()
        ->assertJsonPath('data.name', 'Habeeb Updated');

    expect($employee->fresh()->name)->toBe('Habeeb Updated');
});

it('deletes employee via api', function () {
    $this->seed(EmployeeSeeder::class);
    $employee = Employee::query()->where('name', 'Nizar')->first();
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->deleteJson("/api/employees/{$employee->id}")
        ->assertOk()
        ->assertJsonPath('message', 'Employee deleted successfully.');

    expect(Employee::query()->whereKey($employee->id)->exists())->toBeFalse();
});

it('rejects employee delete without token', function () {
    $employee = Employee::factory()->create();

    $this->deleteJson("/api/employees/{$employee->id}")->assertUnauthorized();
});
