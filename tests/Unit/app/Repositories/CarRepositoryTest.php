<?php

declare(strict_types=1);

namespace Tests\Unit\App\Repositories;

use App\Car;
use App\Repositories\CarRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery;
use Mockery\MockInterface;
use Tests\MockTestValuesEnum;
use Tests\TestCase;

class CarRepositoryTest extends TestCase
{
    private MockInterface $mockModel;
    private CarRepository $repository;

    protected function setUp(): void
    {
        $this->mockModel = Mockery::mock(Car::class);
        $this->repository = new CarRepository($this->mockModel);
    }

    public function testGetAllCarsAsCollectionMethod(): void
    {
        $mockCollection = $this->createMock(Collection::class);
        $this->mockModel->shouldReceive('all')
            ->once()
            ->andReturn($mockCollection);
        $repositoryResponse = $this->repository->getAllCarsAsCollection();
        self::assertSame($mockCollection, $repositoryResponse);
    }

    public function testGetSingleCarModelMethod(): void
    {
        $mockModel = $this->createMock(Car::class);
        $this->mockModel->shouldReceive('findOrFail')
            ->once()
            ->with(MockTestValuesEnum::MOCK_INT)
            ->andReturn($mockModel);

        $repositoryResponse = $this->repository->getSingleCarModel(MockTestValuesEnum::MOCK_INT);
        self::assertSame($mockModel, $repositoryResponse);
    }

    public function testGetSingleCarModelMethodWithExceptionThrownOnFind(): void
    {
        $this->mockModel->shouldReceive('findOrFail')
            ->once()
            ->with(MockTestValuesEnum::MOCK_INT)
            ->andThrow(ModelNotFoundException::class);

        self::expectException(ModelNotFoundException::class);
        $this->repository->getSingleCarModel(MockTestValuesEnum::MOCK_INT);
    }

    public function testStoreNewCarMethod(): void
    {
        $this->mockModel->shouldReceive('create')
            ->once()
            ->with(MockTestValuesEnum::MOCK_ARRAY);
        $this->repository->storeNewCar(MockTestValuesEnum::MOCK_ARRAY);
    }

    public function testDeleteSingleCarByIdMethod(): void
    {
        $this->mockModel->shouldReceive('destroy')
            ->once()
            ->with(MockTestValuesEnum::MOCK_INT);
        $this->repository->deleteSingleCarById(MockTestValuesEnum::MOCK_INT);
    }
}
