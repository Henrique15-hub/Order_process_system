<?php

namespace App\Http\Requests\Merchandise;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MerchandiseStoreRequest extends FormRequest
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

            'price' => [
                'required',
                'numeric',
                'min:0.01'
            ],

            'stock_quantity' => [
                'required',
                'integer',
                'min:1.00'
            ],
        ];
    }
}