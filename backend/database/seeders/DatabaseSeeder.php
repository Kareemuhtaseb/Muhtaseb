<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            PropertyTypeSeeder::class,
            UnitTypeSeeder::class,
            PaymentMethodSeeder::class,
            TestDataSeeder::class,
        ]);
    }
}
