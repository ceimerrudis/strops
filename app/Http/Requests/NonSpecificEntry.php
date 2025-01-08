<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\EntryTypes; 

class NonSpecificEntry extends FormRequest
{
    public function rules(): array
    {
        return [
            'table' => [
                'required',
                'integer',
                new Enum(EntryTypes::class),//Pārbauda vai padotā vērtība atrodama enumeratorā.
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
