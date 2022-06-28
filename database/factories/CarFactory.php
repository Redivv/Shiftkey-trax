<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Car;
use Faker\Generator;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarFactory extends Factory
{
    protected $model = Car::class;

    protected function withFaker(): Generator
    {
        //override required for testing
        return \Faker\Factory::create('en');
    }

    public function definition(): array
    {
        return [
            'make' => $this->faker->company,
            'model' => $this->faker->words(3, true),
            'year' => $this->faker->year()
        ];
    }
}
