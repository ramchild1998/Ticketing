<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call([
            ProvincesTableSeeder::class,
            CitiesTableSeeder::class,
            DistrictsTableSeeder::class,
            SubdistrictsTableSeeder::class,
            PostalcodesTableSeeder::class,
            UsersTableSeeder::class,
            VendorsTableSeeder::class,
            OfficesTableSeeder::class,
            AreaTableSeeder::class,
        ]);

        // Aktifkan kembali foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
