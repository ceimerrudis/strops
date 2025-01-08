<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetCalendar extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'year' => 'required|integer|min:1000|max:3000',
            'month' => 'required|integer|min:1|max:12',
       ];
        return $rules;
    }

    public function messages()
    {
        return [
        ];
    }
}
