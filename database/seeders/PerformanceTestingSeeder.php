<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Laravel\Telescope\Telescope;

class PerformanceTestingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Telescope::stopRecording();

//        $this->call([
//            RoleSeeder::class,
//            PermissionSeeder::class
//        ]);
//
//        // CREATE ADMIN USER
//        User::factory()->create([
//            'name' => 'Mohamed Elsheshtawy',
//            'email' => 'ramyalfe22@gmail.com',
//        ])->assignRole(Role::ROLE_ADMINISTRATOR);

        $this->callWith(Performance\UserSeeder::class, [
            'owners' => 1,
            'users' => 1
        ]);

        $this->callWith(Performance\CountrySeeder::class, [
            'count' => 5000
        ]);
        $this->callWith(Performance\CitySeeder::class, [
            'count' => 6000
        ]);
        $this->callWith(Performance\GeoobjectSeeder::class, [
            'count' => 10000
        ]);
        $this->callWith(Performance\PropertySeeder::class, [
            'count' => 20000
        ]);
        $this->callWith(Performance\ApartmentSeeder::class, [
            'count' => 20000
        ]);

        $this->callWith(Performance\BookingSeeder::class, [
            'withRatings' => 20000,
            'withoutRatings' => 20000
        ]);
    }
}
