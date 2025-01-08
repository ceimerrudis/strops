<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StartVehicleUseCalendarData extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'vehicle' => 'required|exists:vehicles,id',
       ];
        return $rules;
    }

    public function messages()
    {
        return [
            'vehicle.required' => Text(123),
            'vehicle.exists' => Text(124),
        ];
    }
}