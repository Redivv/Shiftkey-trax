<?php

declare(strict_types=1);

namespace Tests\Functional\App\Repositories;

use App\Car;
use App\Repositories\TripRepository;
use App\Trip;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\MockTestValuesEnum;
use Tests\TestCase;

class TripRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private TripRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $testModel = new Trip();
        $this->repository = new TripRepository($testModel);
    }

    public function testGetAllTripsAsCollectionMethod(): void
    {
        $testCar = Car::factory()->create();
        $testTrip1 = Trip::factory()->create([
            'date' => Carbon::today(),
            'car_id' => $testCar->id
        ]);
        $testTrip2 = Trip::factory()->create([
            'date' => Carbon::today()->addDay(),
            'car_id' => $testCar->id
        ]);
        $testTrip3 = Trip::factory()->create([
            'date' => Carbon::today()->addDays(2),
            'car_id' => $testCar->id
        ]);
        $respositoryCollection = $this->repository->getAllTripsAsCollection();

        self::assertTrue($respositoryCollection->count() >= 3);

        self::greaterThan($respositoryCollection[0]->id, $respositoryCollection[1]->id);
        self::greaterThan($respositoryCollection[1]->id, $respositoryCollection[2]->id);

        $respositoryCollection = $respositoryCollection->keyBy('id');

        self::assertTrue($respositoryCollection->has($testTrip1->id));
        self::assertTrue($respositoryCollection->has($testTrip2->id));
        self::assertTrue($respositoryCollection->has($testTrip3->id));

        self::assertTrue($respositoryCollection[$testTrip1->id]->miles_balance === $testTrip1->miles);
        self::assertTrue($respositoryCollection[$testTrip2->id]->miles_balance === $testTrip1->miles + $testTrip2->miles);
        self::assertTrue($respositoryCollection[$testTrip3->id]->miles_balance === $testTrip1->miles + $testTrip2->miles + $testTrip3->miles);
    }

    public function testStoreNewTripMethod(): void
    {
        $testUnpersistedTrip = Trip::factory()->make();
        $this->repository->storeNewTrip($testUnpersistedTrip->toArray());

        $persistedTrip = Trip::orderBy('id', 'desc')->first();
        self::assertTrue($persistedTrip->date->timestamp === $testUnpersistedTrip->date->timestamp);
        self::assertTrue($persistedTrip->miles === $testUnpersistedTrip->miles);
        self::assertTrue($persistedTrip->car_id === $testUnpersistedTrip->car_id);
    }

    public function testStoreNewTripMethodWithInvalidTripData(): void
    {
        self::expectException(InvalidArgumentException::class);
        $this->repository->storeNewTrip(MockTestValuesEnum::MOCK_ARRAY);
    }
}
