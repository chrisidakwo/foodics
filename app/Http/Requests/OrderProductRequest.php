<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class OrderProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'products' => ['required', 'array'],
            'products.*.product_id' => ['required', 'exists:products,id'],
            'products.*.quantity' => ['required', 'numeric'],
        ];
    }

    public function messages(): array
    {
        $messages = parent::messages();

        return array_merge($messages, [
            'products.*.product_id.required' => 'The product id field is required.',
            'products.*.quantity.required' => 'The quantity field is required.',
        ]);
    }
}
