<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        $this->call(PermissionSeeder::class);

        User::factory()->create([
            'name' => 'Mohamed Elsheshtawy',
            'email' => 'ramyalfe22@gmail.com',
        ])->assignRole(Role::ROLE_ADMINISTRATOR);     // admin user

        User::factory()->create()->assignRole(Role::ROLE_OWNER);
        User::factory()->create()->assignRole(Role::ROLE_USER);


        $this->call(CountrySeeder::class);
        $this->call(CitySeeder::class);
        $this->call(GeoobjectSeeder::class);

        $this->call(ApartmentTypeSeeder::class);
        $this->call(RoomTypeSeeder::class);
        $this->call(BedTypeSeeder::class);

        $this->call(FacilityCategorySeeder::class);
        $this->call(FacilitySeeder::class);
    }
}
