<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateReport extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'object' => 'required|exists:objects:id',
            'year' => 'required|integer|min:1000|max|3000',
            'month' => 'required|integer|min:0|max:11',
            'progress' => 'required|numeric|min:0|max:100'
       ];
        return $rules;
    }

    public function messages()
    {
        return [
            'object.required' => Text(145),
            'object.exists' => Text(146),
            'progress.required' => Text(148),
            'progress.numeric' => Text(149),
            'progress.min' => Text(150),
            'progress.max' => Text(150),
        ];
    }
}
