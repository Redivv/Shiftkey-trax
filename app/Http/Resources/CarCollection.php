<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CarCollection extends ResourceCollection
{
    /** @inheritdoc */
    public function toArray($request)
    {
        return [
            'data' => $this->collection
        ];
    }
}
