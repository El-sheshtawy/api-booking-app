<?php

namespace Database\Seeders\Performance;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(int $owners = 100, int $users = 100): void
    {
        User::factory($owners)->create()->each(function ($owner) {
            return $owner->assignRole(Role::ROLE_OWNER);
        });

        User::factory($users)->create()->each(function ($user) {
            return $user->assignRole(Role::ROLE_USER);
        });
    }
}
