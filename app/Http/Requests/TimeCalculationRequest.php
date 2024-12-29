<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TimeCalculationRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'from' => 'required|datetime',
            'until' => 'required|datetime|after:from',
       ];
        return $rules;
    }

    public function messages()
    {
        return [
            'from.required' => text(113),
            'from.datetime' => text(114),

            'until.required' => text(113),
            'until.datetime' => text(114),
            'until.after' => text(115),
        ];
    }
}
