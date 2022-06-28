<?php

declare(strict_types=1);

namespace Tests\Unit\App\Http\Controllers;

use App\Http\Controllers\TripController;
use App\Http\Requests\StoreTripRequest;
use App\Http\Resources\TripCollection;
use App\Repositories\TripRepository;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\MockTestValuesEnum;
use Tests\TestCase;

class TripControllerTest extends TestCase
{

    private TripController $controller;
    private MockObject $mockRepo;

    protected function setUp(): void
    {
        $this->mockRepo = $this->createMock(TripRepository::class);
        $this->controller = new TripController($this->mockRepo);
    }

    public function testIndexMethod(): void
    {
        $mockCollection = $this->createMock(Collection::class);
        $this->mockRepo->expects(self::once())
            ->method('getAllTripsAsCollection')
            ->willReturn($mockCollection);

        $controllerResponse = $this->controller->index();
        self::assertInstanceOf(TripCollection::class, $controllerResponse);
    }

    public function testStoreMethod(): void
    {
        $mockRequest = $this->createMock(StoreTripRequest::class);
        $mockRequest->expects(self::once())
            ->method('validated')
            ->willReturn(MockTestValuesEnum::MOCK_ARRAY);

        $this->mockRepo->expects(self::once())
            ->method('storeNewTrip')
            ->with(MockTestValuesEnum::MOCK_ARRAY);

        $this->controller->store($mockRequest);
    }
}
