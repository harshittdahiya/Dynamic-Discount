<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use MongoDB\Laravel\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id', 
        'product_name', 
        'product_slug', 
        'product_image', 
        'price', 
        'stock', 
        'description', 
        'status'
    ];
    public function getImageUrlAttribute()
    {
        if (! $this->product_image) {
            return null;
        }

        if (Str::startsWith($this->product_image, ['http://', 'https://', '//'])) {
            return $this->product_image;
        }

        return asset('storage/' . $this->product_image);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
