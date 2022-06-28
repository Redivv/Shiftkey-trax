<?php

declare(strict_types=1);

namespace Tests\Functional\App\Repositories;

use App\Car;
use App\Repositories\CarRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\MockTestValuesEnum;
use Tests\TestCase;


class CarRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private CarRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $testModel = new Car();
        $this->repository = new CarRepository($testModel);
    }

    public function testGetAllCarsAsCollectionMethod(): void
    {
        [$testCar1, $testCar2, $testCar3] = Car::factory()->count(3)->create();
        $respositoryCollection = $this->repository->getAllCarsAsCollection();

        self::assertTrue($respositoryCollection->count() >= 3);

        self::greaterThan($respositoryCollection[0]->id, $respositoryCollection[1]->id);
        self::greaterThan($respositoryCollection[1]->id, $respositoryCollection[2]->id);

        $respositoryCollection = $respositoryCollection->keyBy('id');

        self::assertTrue($respositoryCollection->has($testCar1->id));
        self::assertTrue($respositoryCollection->has($testCar2->id));
        self::assertTrue($respositoryCollection->has($testCar3->id));
    }

    public function testGetSingleCarModelMethod(): void
    {
        $testCar = Car::factory()->create();
        $repositoryCar = $this->repository->getSingleCarModel($testCar->id);

        self::assertTrue($repositoryCar->is($testCar));
    }

    public function testGetSingleCarModelMethodWithInvalidCarId(): void
    {
        self::expectException(ModelNotFoundException::class);
        $this->repository->getSingleCarModel(MockTestValuesEnum::MOCK_INT);
    }

    public function testStoreNewCarMethod(): void
    {
        $testUnpersistedCar = Car::factory()->make();
        $this->repository->storeNewCar($testUnpersistedCar->toArray());

        $persistedCar = Car::orderBy('id', 'desc')->first();

        self::assertTrue($persistedCar->make === $testUnpersistedCar->make);
        self::assertTrue($persistedCar->model === $testUnpersistedCar->model);
        self::assertTrue($persistedCar->year === $testUnpersistedCar->year);
    }

    public function testStoreNewCarMethodWithInvalidCarData()
    {
        self::expectException(InvalidArgumentException::class);
        $this->repository->storeNewCar(MockTestValuesEnum::MOCK_ARRAY);
    }

    public function testDeleteSingleCarByIdMethod(): void
    {
        $testCar = Car::factory()->create();
        $this->repository->deleteSingleCarById($testCar->id);

        self::assertNull(Car::find($testCar->id));
    }

    public function testDeleteSingleCarByIdMethodWithInvalidCarId(): void
    {
        self::expectException(ModelNotFoundException::class);
        $this->repository->deleteSingleCarById(MockTestValuesEnum::MOCK_INT);
    }
}
