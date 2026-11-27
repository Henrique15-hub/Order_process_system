<?php

namespace App\Http\Requests\Merchandise;


use Illuminate\Foundation\Http\FormRequest;

class MerchandiseUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'public_id' => [
                'required',
                'exists:merchandises,public_id'
            ],

            'name' => [
                'nullable',
                'string',
            ],

            'price' => [
                'nullable',
                'numeric'
            ],

            'stock_quantity' => [
                'nullable',
                'integer',
                'min:1'
            ],
        ];
    }
}