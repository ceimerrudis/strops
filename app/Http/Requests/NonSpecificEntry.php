<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule; 
use App\Enums\EntryTypes; 

class NonSpecificEntry extends FormRequest
{
    public function rules(): array
    {
        return [
            'table' => [
                'required',
                'integer',
                Rule::in(array_column(EntryTypes::cases(), 'value')),//Pārbauda vai padotā vērtība atrodama sarakstā.
            ],
        ];
    }

    public function messages()
    {
        return [
            'table.required' => Text(105),
            'table.integer' => Text(106),
        ];
    }

}
