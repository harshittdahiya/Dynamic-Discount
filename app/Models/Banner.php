<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use MongoDB\Laravel\Eloquent\Model;

#[Fillable(['title', 'subtitle', 'banner_image', 'start_date', 'end_date', 'status'])]
class Banner extends Model
{
    public function scopeActive(Builder $query): Builder
    {
        $today = now()->toDateString();
        
        return $query->where('status', 'active')
            ->where(function ($q) use ($today) {
                $q->whereNull('start_date')
                  ->orWhere('start_date', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', $today);
            });
    }
}
