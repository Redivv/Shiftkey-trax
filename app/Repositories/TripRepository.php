<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Trip;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class TripRepository
{

    public function __construct(private Trip $tripModel)
    {
    }

    public function getAllTripsAsCollection(): Collection
    {
        return $this->tripModel->query()
            ->select(
                'trips.id',
                'trips.date',
                'trips.miles',
                'trips.car_id',
                'cars.make',
                'cars.model',
                'cars.year',
                DB::raw('SUM(trips.miles) OVER(PARTITION BY trips.car_id ORDER BY trips.date) AS miles_balance')
            )
            ->join('cars', 'trips.car_id', '=', 'cars.id')
            ->orderBy('trips.id')
            ->get();
    }

    public function storeNewTrip(array $tripData): void
    {
        try {
            $this->tripModel->create($tripData);
        } catch (QueryException $e) {
            throw new InvalidArgumentException('Invalid trip data supplied');
        }
    }
}
