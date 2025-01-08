<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ViewReport extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'id' => 'required|exists:reports,id',
       ];
        return $rules;
    }

    public function messages()
    {
        return [
        ];
    }
}
