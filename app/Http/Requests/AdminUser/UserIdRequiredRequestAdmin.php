<?php

namespace App\Http\Requests\AdminUser;

use Illuminate\Foundation\Http\FormRequest;

class UserIdRequiredRequestAdmin extends FormRequest
{

    public function rules(): array
    {
        return [
            'id' => [
                'required',
                'exists:users,id'
            ]
            ,
        ];
    }
}
