<?php

declare(strict_types=1);

namespace App\Observers;

use App\Trip;
use Carbon\Carbon;

class TripObserver
{
    public function creating(Trip $trip): void
    {
        // parse frontend datetime to cut out the redundant time recorded on click
        $trip->date = Carbon::parse($trip->date)->toDate();
    }
}
