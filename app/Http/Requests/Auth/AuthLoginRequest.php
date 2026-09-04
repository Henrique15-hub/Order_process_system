<?php

namespace App\Http\Requests\Auth;


use Illuminate\Foundation\Http\FormRequest;

class AuthLoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "email" => [
                'required_without:cnpj',
                'email',
                'string',
                'exists:users,email'
            ],

            "cnpj" => [
                'required_without:email',
                'cnpj',
                'string',
                'exists:users,cnpj'
            ],

            "password" => [
                'required',
                'string',
            ],
        ];
    }
}
