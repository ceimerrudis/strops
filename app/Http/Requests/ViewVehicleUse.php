<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EndVehicleUse extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'vehicleUse' => 'required|exists:vehicleUses,id',
       ];
        return $rules;
    }

    public function messages()
    {
        return [
            'vehicleUse.required' => Text(131),
            'vehicleUse.exists' => Text(132),
        ];
    }
}