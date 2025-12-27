<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'barcode',
        'video_url',
        'description',
        'admin_note',
        'price',
        'discount_percent',
        'cost_price',
        'stock',
        'image',
        'is_active'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class)->withPivot('value')->withTimestamps();
    }

    public function getSalePriceAttribute(): float
    {
        if ($this->discount_percent > 0) {
            $discountAmount = ($this->price * $this->discount_percent) / 100;
            return (float) ($this->price - $discountAmount);
        }
        return (float) $this->price;
    }

    public function getIsOnSaleAttribute(): bool
    {
        return $this->discount_percent > 0;
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->sale_price, 2) . ' m';
    }
}
