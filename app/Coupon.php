<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vanilo\Promotion\Models\Promotion;

class Coupon extends Model
{
    protected $fillable = [
        'promotion_id',
        'code',
        'usage_limit',
        'per_customer_usage_limit',
        'usage_count',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'usage_limit' => 'integer',
        'per_customer_usage_limit' => 'integer',
        'usage_count' => 'integer',
    ];

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isUsable(): bool
    {
        if ($this->isExpired()) {
            return false;
        }

        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }
}
