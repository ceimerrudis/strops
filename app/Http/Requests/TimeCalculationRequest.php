<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TimeCalculationRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'from' => 'required|date',
            'until' => 'required|date|after:from',
       ];
        return $rules;
    }

    public function messages()
    {
        return [
            'from.required' => Text(113),
            'from.date' => Text(114),

            'until.required' => Text(113),
            'until.date' => Text(114),
            'until.after' => Text(115),
        ];
    }
}
