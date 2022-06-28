<?php

use Database\Seeders\CarSeeder;
use Database\Seeders\TripSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CarSeeder::class,
            TripSeeder::class
        ]);
    }
}
