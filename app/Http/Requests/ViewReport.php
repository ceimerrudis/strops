<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ViewReport extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'object' => 'required|exists:objects:id',
            'report' => 'required|exists:reports:id',
       ];
        return $rules;
    }

    public function messages()
    {
        return [
            'object.required' => Text(145),
            'object.exists' => Text(146),
        ];
    }
}
