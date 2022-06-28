<?php

namespace Database\Seeders;

use App\Trip;
use Illuminate\Database\Seeder;

class TripSeeder extends Seeder
{

    public function run(): void
    {
        Trip::factory()
            ->count(3)
            ->forCar()
            ->create();
    }
}
