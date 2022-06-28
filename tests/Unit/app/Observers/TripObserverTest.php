<?php

declare(strict_types=1);

namespace Tests\Unit\App\Observers;

use App\Observers\TripObserver;
use App\Trip;
use Carbon\Carbon;
use Tests\TestCase;

class TripObserverTest extends TestCase
{
    private TripObserver $observer;

    protected function setUp(): void
    {
        $this->observer = new TripObserver();
    }

    public function testCreatingMethod(): void
    {
        $carbonTodayIsoString = Carbon::today()->format(Carbon::ISO8601);

        $mockTrip = $this->createMock(Trip::class);
        $mockTrip->expects(self::once())
            ->method('__get')
            ->with('date')
            ->willReturn($carbonTodayIsoString);
        $mockTrip->expects(self::once())
            ->method('__set')
            ->with('date', Carbon::today()->toDate());

        $this->observer->creating($mockTrip);
    }
}
