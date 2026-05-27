<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'discount',
        'discount_type',
        'max_uses',
        'used_count',
        'min_amount',
        'expires_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'discount' => 'decimal:2',
            'min_amount' => 'decimal:2',
            'max_uses' => 'integer',
            'used_count' => 'integer',
            'expires_at' => 'date',
        ];
    }

    public function isValid(): bool
    {
        if ($this->status !== 'active') return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->max_uses && $this->used_count >= $this->max_uses) return false;
        return true;
    }
}
