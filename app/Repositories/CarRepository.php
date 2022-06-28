<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Car;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use InvalidArgumentException;

class CarRepository
{

    public function __construct(private Car $carModel)
    {
    }

    public function getAllCarsAsCollection(): Collection
    {
        return $this->carModel->all();
    }

    public function getSingleCarModel(int $carId): Car
    {
        return $this->carModel->findOrFail($carId);
    }

    public function storeNewCar(array $carData): void
    {
        try {
            $this->carModel->create($carData);
        } catch (QueryException $e) {
            throw new InvalidArgumentException('Invalid car data supplied');
        }
    }

    public function deleteSingleCarById(int $carId): void
    {
        if ($this->carModel->destroy($carId) === 0) {
            throw new ModelNotFoundException('Invalid car identifier');
        }
    }
}
