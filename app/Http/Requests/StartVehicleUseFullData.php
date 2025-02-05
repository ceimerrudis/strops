<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StartVehicleUseFullData extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'until' => 'nullable|date|after:now',
            'endCurrentUsage' => 'required|string',
            'vehicle' => 'required|exists:vehicles,id',
            'object' => 'required|exists:objects,id',
            'comment' => 'string|nullable',
            'usage' => 'nullable|numeric',
       ];
        return $rules;
    }

    public function messages()
    {
        return [
            'vehicle.required' => Text(123),
            'vehicle.exists' => Text(124),
            'usage.numeric' =>  Text(125),
            'object.required' =>Text(126),
            'object.exists' =>  Text(127),
            'comment.string' => Text(128),

            'until.date' => Text(114),
            'until.after' => Text(115),
        ];
    }
}
