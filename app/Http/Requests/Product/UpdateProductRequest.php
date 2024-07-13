<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $productId = $this->route('product');

        return [
            'product_name' => [
                'string',
                'max:255',
                Rule::unique('products')->ignore($productId),
            ],
            'description' => 'string',
            'price' => 'numeric|regex:/^\d+(\.\d{2})?$/',
            //'product_image' => 'nullable|image',
            'category_id' => 'exists:categories,id',
        ];
    }
}
