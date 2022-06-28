<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreTripRequest;
use App\Http\Resources\TripCollection;
use App\Repositories\TripRepository;

class TripController extends Controller
{

    public function __construct(private TripRepository $tripRepository)
    {
        $this->middleware('auth:api');
    }

    public function index(): TripCollection
    {
        $tripsCollection = $this->tripRepository->getAllTripsAsCollection();
        return new TripCollection($tripsCollection);
    }

    public function store(StoreTripRequest $request): void
    {
        $this->tripRepository->storeNewTrip($request->validated());
    }
}
