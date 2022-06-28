<?php

declare(strict_types=1);

namespace Tests\Unit\App\Repositories;

use App\Repositories\TripRepository;
use App\Trip;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Mockery;
use Mockery\MockInterface;
use Tests\MockTestValuesEnum;
use Tests\TestCase;

class TripRepositoryTest extends TestCase
{
    private MockInterface $mockModel;
    private TripRepository $repository;

    protected function setUp(): void
    {
        $this->mockModel = Mockery::mock(Trip::class);
        $this->repository = new TripRepository($this->mockModel);
    }

    public function testGetAllTripsAsCollectionMethod(): void
    {
        $mockCollection = $this->createMock(Collection::class);
        $mockBuilder = Mockery::mock(Builder::class);

        $this->mockModel->shouldReceive('query')
            ->once()
            ->andReturn($mockBuilder);
        DB::shouldReceive('raw')
            ->once();
        $mockBuilder->shouldReceive('select')
            ->once()
            ->andReturnSelf();
        $mockBuilder->shouldReceive('join')
            ->once()
            ->withSomeOfArgs('cars')
            ->andReturnSelf();
        $mockBuilder->shouldReceive('orderBy')
            ->once()
            ->with('trips.id')
            ->andReturnSelf();
        $mockBuilder->shouldReceive('get')
            ->once()
            ->andReturn($mockCollection);

        $repositoryResponse = $this->repository->getAllTripsAsCollection();
        self::assertSame($mockCollection, $repositoryResponse);
    }

    public function testStoreNewTripMethod(): void
    {
        $this->mockModel->shouldReceive('create')
            ->once()
            ->with(MockTestValuesEnum::MOCK_ARRAY);
        $this->repository->storeNewTrip(MockTestValuesEnum::MOCK_ARRAY);
    }
}
