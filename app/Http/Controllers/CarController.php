<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarRequest;
use App\Http\Resources\CarCollection;
use App\Http\Resources\CarResource;
use App\Repositories\CarRepository;

class CarController extends Controller
{

    public function __construct(private CarRepository $carRepository)
    {
        $this->middleware('auth:api');
    }

    public function index(): CarCollection
    {
        $carsCollection = $this->carRepository->getAllCarsAsCollection();
        return new CarCollection($carsCollection);
    }

    public function store(StoreCarRequest $request): void
    {
        $this->carRepository->storeNewCar($request->validated());
    }

    public function show(int $carId): CarResource
    {
        $car = $this->carRepository->getSingleCarModel($carId);
        return new CarResource($car);
    }

    public function destroy(int $carId): void
    {
        $this->carRepository->deleteSingleCarById($carId);
    }
}
