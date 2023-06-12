<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();]

        $this->call([
            EmailSettingSeeder::class,
            CompanySettingSeeder::class,
            CategorySeeder::class,
            IndoRegionSeeder::class,
            CountrySeeder::class,
            RolePermissionSeeder::class,
            CreateUserSeeder::class,
            ManualGateawaySeeder::class,
            AutomaticPaymentSeeder::class,
        ]);
    }
}
