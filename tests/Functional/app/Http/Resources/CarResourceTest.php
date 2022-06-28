<?php

declare(strict_types=1);

namespace Tests\Functional\App\Http\Resources;

use App\Car;
use App\Http\Resources\CarResource;
use App\Repositories\CarRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class CarResourceTest extends TestCase
{
    use RefreshDatabase;

    public function testToArrayMethod(): void
    {
        $testCar = Car::factory()->hasTrips(3)->create();
        $resource = new CarResource($testCar);

        $resourceToArray = $resource->toArray(new Request());
        self::assertSame([
            "id" => $testCar->id,
            "make" => $testCar->make,
            "model" => $testCar->model,
            "year" => $testCar->year,
            "trip_count" => $testCar->trips->count(),
            "trip_miles" => $testCar->trips->sum('miles')
        ], $resourceToArray);
    }

    public function testToArrayMethodWithRepositoryRetrivedTrip(): void
    {
        $testCar = Car::factory()->hasTrips(3)->create();

        $carFromRepository = (new CarRepository(new Car()))->getSingleCarModel($testCar->id);
        $resource = new CarResource($carFromRepository);

        $resourceToArray = $resource->toArray(new Request());
        self::assertSame([
            "id" => $testCar->id,
            "make" => $testCar->make,
            "model" => $testCar->model,
            "year" => $testCar->year,
            "trip_count" => $testCar->trips->count(),
            "trip_miles" => $testCar->trips->sum('miles')
        ], $resourceToArray);
    }

    public function testToArrayMethodWithRepositoryRetrivedTripFromCollection(): void
    {
        $testCar = Car::factory()->hasTrips(3)->create();

        $carsFromRepository = (new CarRepository(new Car()))->getAllCarsAsCollection();
        $carsFromRepository = $carsFromRepository->keyBy('id');
        $resource = new CarResource($carsFromRepository[$testCar->id]);

        $resourceToArray = $resource->toArray(new Request());
        self::assertSame([
            "id" => $testCar->id,
            "make" => $testCar->make,
            "model" => $testCar->model,
            "year" => $testCar->year,
            "trip_count" => $testCar->trips->count(),
            "trip_miles" => $testCar->trips->sum('miles')
        ], $resourceToArray);
    }
}
