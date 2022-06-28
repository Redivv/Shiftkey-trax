<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTripRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'date' => 'required|date', // ISO 8601 string
            'car_id' => 'required|integer|exists:cars,id',
            'miles' => 'required|numeric'
        ];
    }
}
