<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use MongoDB\Laravel\Eloquent\Model;

#[Fillable(['category_name', 'category_slug', 'status'])]
class Category extends Model
{
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
