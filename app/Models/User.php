<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['ip', 'first_name', 'last_name', 'date_of_birth', 'email', 'password', 'address'];

    // Use creating event to manipulate address before saving
    /*protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            // Concatenate postal code, country, and city into address field
            $address = "{$user->postal_code}, {$user->country}, {$user->city}";
            $user->address = $address;
        });
    }*/
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'password' => 'hashed',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function ordersByIP()
    {
        return $this->hasMany(Order::class, 'user_ip', 'ip');
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }
}
