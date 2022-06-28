<?php

namespace Database\Seeders;

use App\Car;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{

    public function run(): void
    {
        Car::factory()
            ->count(10)
            ->hasTrips(3)
            ->create();
    }
}
