<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class FailedCouponAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'attempted_code',
        'ip_address',
        'reason'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
