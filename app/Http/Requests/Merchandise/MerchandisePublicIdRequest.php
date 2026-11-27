<?php

namespace App\Http\Requests\Merchandise;

use Illuminate\Foundation\Http\FormRequest;

class MerchandisePublicIdRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'public_id' => [
                'required',
                'exists:merchandises,public_id'
            ],
        ];
    }
}