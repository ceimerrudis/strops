<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginData extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'username' => 'required|exists:users,username',
            'password' => 'required',
       ];
        return $rules;
    }

    public function messages()
    {
        return [
            'username.exists' => text(101),
            'username.required' => text(102),
            'password.required' => text(103),
        ];
    }
}
