<?php

namespace Database\Seeders;

use App\Models\User;
use App\UserRole;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::query()
            ->whereIn('email', [
                'superadmin@maraweb.io',
                'test@example.com',
            ])
            ->delete();

        User::query()->updateOrCreate(
            ['email' => env('SUPER_ADMIN_EMAIL', 'superadmin@gmail.com')],
            [
                'name' => env('SUPER_ADMIN_NAME', 'Super Admin'),
                'password' => env('SUPER_ADMIN_PASSWORD', '1234'),
                'role' => UserRole::SuperAdmin,
                'email_verified_at' => now(),
            ],
        );
    }
}
