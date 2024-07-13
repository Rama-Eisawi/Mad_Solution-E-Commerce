<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use HasFactory;

    // Add the accessor for created_from
    //imaginary column
    protected $appends = ['created_from'];

    public function getCreatedFromAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }
}
