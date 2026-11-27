<?php

namespace App\Http\Requests\AdminUser;
use App\Enums\RoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UserUpdateRequestAdmin extends FormRequest
{

    public function rules(): array
    {
        return [
            'id' => [
                'required',
                'exists:users,id'
            ],

            'name' => [
                'nullable',
                'string'
            ],

            'email' => [
                'nullable',
                'string',
                'email',
            ],

            'role' => [
                'nullable',
                new Enum(RoleEnum::class),
            ],
        ];
    }
}
