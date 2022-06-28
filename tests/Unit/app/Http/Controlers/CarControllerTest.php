<?php

declare(strict_types=1);

namespace Tests\Unit\App\Http\Controllers;

use App\Car;
use App\Http\Controllers\CarController;
use App\Http\Requests\StoreCarRequest;
use App\Http\Resources\CarCollection;
use App\Http\Resources\CarResource;
use App\Repositories\CarRepository;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\MockTestValuesEnum;
use Tests\TestCase;

class CarControllerTest extends TestCase
{
    private CarController $controller;
    private MockObject $mockRepo;

    protected function setUp(): void
    {

        $this->mockRepo = $this->createMock(CarRepository::class);
        $this->controller = new CarController($this->mockRepo);
    }

    public function testIndexMethod(): void
    {
        $mockCollection = $this->createMock(Collection::class);
        $this->mockRepo->expects(self::once())
            ->method('getAllCarsAsCollection')
            ->willReturn($mockCollection);

        $controllerResponse = $this->controller->index();
        self::assertInstanceOf(CarCollection::class, $controllerResponse);
    }

    public function testStoreMethod(): void
    {
        $mockRequest = $this->createMock(StoreCarRequest::class);
        $mockRequest->expects(self::once())
            ->method('validated')
            ->willReturn(MockTestValuesEnum::MOCK_ARRAY);

        $this->mockRepo->expects(self::once())
            ->method('storeNewCar')
            ->with(MockTestValuesEnum::MOCK_ARRAY);

        $this->controller->store($mockRequest);
    }

    public function testShowMethod(): void
    {
        $mockCar = $this->createMock(Car::class);

        $this->mockRepo->expects(self::once())
            ->method('getSingleCarModel')
            ->with(MockTestValuesEnum::MOCK_INT)
            ->willReturn($mockCar);

        $controllerResponse = $this->controller->show(MockTestValuesEnum::MOCK_INT);
        self::assertInstanceOf(CarResource::class, $controllerResponse);
        self::assertSame($mockCar, $controllerResponse->resource);
    }

    public function testDestroyMethod(): void
    {
        $this->mockRepo->expects(self::once())
            ->method('deleteSingleCarById')
            ->with(MockTestValuesEnum::MOCK_INT);

        $this->controller->destroy(MockTestValuesEnum::MOCK_INT);
    }
}
