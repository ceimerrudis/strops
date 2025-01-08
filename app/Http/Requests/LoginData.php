<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginData extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'username' => 'required|regex:/^[\p{L}\p{M}\p{N}_-]+$/u|exists:users,username',
            'password' => 'required',
       ];
        return $rules;
    }

    public function messages()
    {
        return [
            'username.exists' => Text (101),
            'username.required' => Text(102),
            'username.regex' => Text(221),
            'password.required' => Text(103),
        ];
    }
}
