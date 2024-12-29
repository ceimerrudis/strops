<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MakeReservation extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'vehicle' => 'required|exists:vehicles:id',
            'from' => 'required|datetime',
            'until' => 'required|datetime|after:from',
       ];
        return $rules;
    }

    public function messages()
    {
        return [
            'vehicle.required' => Text(141),
            'vehicle.exists' => Text(124),
            'from.required' => Text(113),
            'from.datetime' => Text(114),

            'until.required' => Text(113),
            'until.datetime' => Text(114),
            'until.after' => Text(115),
        ];
    }
}
