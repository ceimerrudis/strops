<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EndVehicleUse extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'vehicle_use' => 'required|exists:vehicle_uses,id',
            'usage' => 'required|numeric',
       ];
        return $rules;
    }

    public function messages()
    {
        return [
            'vehicle_use.required' => Text(131),
            'vehicle_use.exists' => Text(132),
            'usage.numeric' =>  Text(125),
            'usage.required' =>  Text(133),
        ];
    }
}