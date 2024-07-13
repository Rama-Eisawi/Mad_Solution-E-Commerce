<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedFormTrait;

class Category extends BaseModel //category model will inherit from base model
{
    use HasFactory;
    protected $fillable = ['category_name', 'parent_id'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function allChildren()
    {
        return $this->children()->with(['allChildren', 'products']);
    }
}
