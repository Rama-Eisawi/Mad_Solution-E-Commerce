<?php

namespace Database\Seeders;

use App\Models\Product;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductQuantitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all products and update their product_quantity
        Product::all()->each(function ($product) {
            $product->update(['product_quantity' => rand(0, 500)]);
        });
    }
}
