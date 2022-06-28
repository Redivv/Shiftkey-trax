<?php

declare(strict_types=1);

namespace App;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trip extends Model
{
    use HasFactory;

    public const TRIP_DATE_FORMAT = 'm/d/Y';

    protected $fillable = [
        'date',
        'miles',
        'car_id',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'miles' => 'float',
        'miles_balance' => 'float'
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }
}
