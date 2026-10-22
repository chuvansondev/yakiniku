<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (blank($email) || blank($password)) {
            $this->command?->warn('Admin seed skipped. Set ADMIN_EMAIL and ADMIN_PASSWORD to create the initial administrator.');
        } else {
            if (strlen($password) < 12) {
                throw new \InvalidArgumentException('ADMIN_PASSWORD must be at least 12 characters long.');
            }

            User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => env('ADMIN_NAME', 'Admin'),
                    'password' => $password,
                    'is_admin' => true,
                ]
            );
        }

        $this->call(AccountManagementPermissionSeeder::class);
    }
}
