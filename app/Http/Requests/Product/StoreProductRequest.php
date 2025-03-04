<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
        return [
            'product_name' => 'required|unique:products|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|regex:/^\d+(\.\d{2})?$/',
            //'product_image' => 'required|image',
            'category_id' => 'required|exists:categories,id',
        ];
    }
}
