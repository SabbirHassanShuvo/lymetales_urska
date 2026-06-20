<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            ECommerceSeeder::class,
            PagesSeeder::class,
            HomeContentSeeder::class,
            BlogSeeder::class,
        ]);
    }
}
