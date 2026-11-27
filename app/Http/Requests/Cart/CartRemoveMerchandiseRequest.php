<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class CartRemoveMerchandiseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'public_id' => [
                'required',
                'string',
                'exists:merchandises,public_id'
            ],

            'quantity' => [
                'nullable',
                'integer',
                'min:1'
            ],
        ];
    }

    public function messages()
    {
        return [
            'public_id.required' => "The public_id field is required",
            "public_id.exists" => "The provided public_id don't exist in the system",
        ];
    }
}
