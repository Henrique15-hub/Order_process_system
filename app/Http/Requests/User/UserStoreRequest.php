<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
            ],

            'email' => [
                'required_without:cnpj',
                'email',
                'nullable',
                'unique:users,email',
                ''
            ],

            'cnpj' => [
                'required_without:email',
                'cnpj',
                'nullable',
                'unique:users,cnpj',
            ],

            'password' => [
                'required',
                'string'
            ],
        ];
    }
}