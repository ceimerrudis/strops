<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ViewCreateReport extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'object' => 'required|exists:objects,id',
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
