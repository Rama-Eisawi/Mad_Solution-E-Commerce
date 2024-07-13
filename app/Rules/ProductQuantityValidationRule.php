<?php

namespace App\Rules;

use App\Models\Product;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ProductQuantityValidationRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
    }
    public function passes($attribute, $value)
    {
        // Retrieve the product ID from the request
        $product_id = request()->input('product_id');

        // Retrieve the product
        $product = Product::findOrFail($product_id);

        // Check if the order quantity is less than or equal to product quantity
        return $value <= $product->product_quantity;
    }

    public function message()
    {
        return 'The order quantity must be less than or equal to product quantity.';
    }
}
