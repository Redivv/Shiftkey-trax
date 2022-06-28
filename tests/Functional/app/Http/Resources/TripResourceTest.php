<?php

declare(strict_types=1);

namespace Tests\Functional\App\Http\Resources;

use App\Http\Resources\TripResource;
use App\Repositories\TripRepository;
use App\Trip;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class TripResourceTest extends TestCase
{
    use RefreshDatabase;

    public function testToArrayMethod(): void
    {
        $testTrip = Trip::factory()->create();
        $resource = new TripResource($testTrip);

        $resourceToArray = $resource->toArray(new Request());
        self::assertSame([
            "id" => $testTrip->id,
            "date" => $testTrip->date->format('m/d/Y'),
            'miles' => $testTrip->miles,
            "total" => null,
            "car" => [
                "id" => $testTrip->car_id,
                "make" => null,
                "model" => null,
                "year" => null
            ]
        ], $resourceToArray);
    }

    public function testToArrayMethodWithRepositoryRetrivedTripFromCollection(): void
    {
        $testTrip = Trip::factory()->create([
            'date' => Carbon::today()->addDay()
        ]);
        $testTrip2 = Trip::factory()->create([
            'car_id' => $testTrip->car_id,
            'date' => Carbon::today()
        ]);
        $tripsFromRepository = (new TripRepository(new Trip()))->getAllTripsAsCollection();
        $tripsFromRepository = $tripsFromRepository->keyBy('id');

        $resource = new TripResource($tripsFromRepository[$testTrip->id]);

        $resourceToArray = $resource->toArray(new Request());
        self::assertSame([
            "id" => $testTrip->id,
            "date" => $testTrip->date->format('m/d/Y'),
            'miles' => $testTrip->miles,
            "total" => $testTrip->miles + $testTrip2->miles,
            "car" => [
                "id" => $testTrip->car_id,
                "make" => $testTrip->car->make,
                "model" => $testTrip->car->model,
                "year" => (int)$testTrip->car->year
            ]
        ], $resourceToArray);
    }
}
