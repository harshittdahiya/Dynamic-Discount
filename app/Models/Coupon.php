<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use MongoDB\Laravel\Eloquent\Model;

#[Fillable([
    'coupon_code',
    'discount_type',
    'discount_value',
    'min_purchase',
    'max_discount',
    'expiry_date',
    'usage_limit',
    'per_user_limit',
    'status'
])]
class Coupon extends Model
{
    protected $casts = [
        'expiry_date' => 'datetime',
    ];

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function isValid(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->expiry_date && $this->expiry_date->isPast()) {
            return false;
        }

        return true;
    }

    public function registerUsage($userId, $orderId = null)
    {
        return $this->usages()->create([
            'user_id' => $userId,
            'order_id' => $orderId,
            'used_at' => now(),
        ]);
    }
}
