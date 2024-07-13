<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 15 parent categories
        $parentCategories = Category::factory()->count(15)->create();

        // Create subcategories for each parent category
        $this->createSubcategories($parentCategories);
    }

    protected function createSubcategories($categories, $depth = 0)
    {
        foreach ($categories as $category) {
            // Randomly choose the number of subcategories (0 to 5)
            $numberOfSubcategories = rand(0, 5);

            // Create subcategories for the current category
            $subcategories = Category::factory()->count($numberOfSubcategories)->create(['parent_id' => $category->id]);

            // Recursively create subcategories for the newly created subcategories
            if ($depth < 3) { // Limit the depth to avoid infinite recursion
                $this->createSubcategories($subcategories, $depth + 1);
            }
        }
    }
}
