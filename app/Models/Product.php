<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends BaseModel //product model will inherit from base model
{
    use HasFactory;
    protected $fillable = ['product_name', 'description', 'price', 'category_id'];


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
