<?php

namespace Database\Factories;

use App\Car;
use App\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

class TripFactory extends Factory
{
    protected $model = Trip::class;

    public function definition(): array
    {
        return [
            'date' => $this->faker->date(),
            'miles' => $this->faker->randomFloat(2, 5, 120),
            'car_id' => Car::factory()
        ];
    }
}
