<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\{Factories\HasFactory, Model};

class Order extends Model
{
    public $timestamps = false;

    use HasFactory;
    protected $fillable = ['user_id', 'user_ip', 'product_id', 'order_quantity', 'status', 'delivered_at', 'created_at'];

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value;

        if ($value == 'delivered') {
            $this->attributes['delivered_at'] = Carbon::now();
        }
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function userByIP()
    {
        return $this->belongsTo(User::class);
    }
}
