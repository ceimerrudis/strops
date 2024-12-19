<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\RequestHelperFunctions;

class SpecificEntry extends FormRequest
{
    public function rules(): array
    {
        return [
            'table' => [
                'required|integer',
                Rule::in(array_column(EntryTypes::cases(), 'value')),//Pārbauda vai padotā vērtība atrodama sarakstā.
            ],
            'id' => [
                'required|integer',
                function ($attribute, $value, $fail) {//Pārbauda vai norādītajā tabulā eksistē ieraksts ar šo id.
                    $table = $this->input('table');
                    if (!EntryHelper::idExistsInTable($table, $value)) {
                        $fail(text(104));
                    }
                },
            ],
        ];
    }

    public function messages()
    {
        return [
            'table.required' => text(105),
            'table.integer' => text(106),
            'id.required' => text(107),
            'id.integer' => text(108),
        ];
    }

}
