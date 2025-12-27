<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'admin_notes',
        'address',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'permissions' => 'array',
    ];
    
    public function hasPermission($section): bool
    {
        // Admin always has permission
        if ($this->role === 'admin') return true;

        // Check if operator has the specific permission set to true
        return isset($this->permissions[$section]) && $this->permissions[$section] === true;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isOperator(): bool
    {
        return $this->role === 'operator';
    }

    public function isStaff(): bool
    {
        return in_array($this->role, ['admin', 'operator']);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(Product::class, 'favorites');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
