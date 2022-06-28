<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Trip;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{
    /** @inheritdoc */
    public function toArray($request): array
    {
        return
            [
                'id'  => $this->id,
                'date' => $this->date->format(Trip::TRIP_DATE_FORMAT),
                'miles' => $this->miles,
                'total' => $this->miles_balance,
                'car' => [
                    'id' => $this->car_id,
                    'make' => $this->make,
                    'model' => $this->model,
                    'year' => $this->year
                ]
            ];
    }
}
