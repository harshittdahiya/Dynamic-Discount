<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class CouponUsage extends Model
{
    protected $fillable = [
        'user_id',
        'coupon_id',
        'order_id',
        'used_at'
    ];

    protected $casts = [
        'used_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
