<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'make',
        'model',
        'year',
    ];

    protected $casts = [
        'year' => 'string',
    ];

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }
}
