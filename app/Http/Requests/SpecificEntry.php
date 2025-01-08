<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;
use App\RequestHelperFunctions;
use App\Enums\EntryTypes;

class SpecificEntry extends FormRequest
{
    public function rules(): array
    {
        return [
            'table' => [
                'required',
                'integer',
                new Enum(EntryTypes::class),//Pārbauda vai padotā vērtība atrodama enumeratorā.
            ],
            'id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {//Pārbauda vai norādītajā tabulā eksistē ieraksts ar šo id.
                    if ($this->input('table') != null) {//Nezinu vai varu paļauties uz to ka  table ir validēts
                        $modelClass = GetModelFromEnum((int)$this->input('table'));
                        if ($modelClass === false) {//$modelClass ir false ja neizdevās
                            $fail(Text(154));
                        } 
                        else if (!$modelClass::query()->where('id', $value)->exists()) {
                            $fail(Text(155));
                        }
                    }
                },
            ],
        ];
    }

    public function messages()
    {
        return [
            'table.required' => Text(105),
            'table.integer' => Text(106),
            'id.required' => Text(107),
            'id.integer' => Text(108),
        ];
    }
}
