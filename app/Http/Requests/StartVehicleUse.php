<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StartVehicleUse extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'vehicle' => 'required|exists:vehicles,id',
            'usage' => 'nullable|numeric',
            'usageCorrect' => 'required',
            'endCurrentUsage' => 'required',
            'object' => 'required|exists:objects,id',
            'comment' => 'string|nullable',
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
        ];
    }
}
