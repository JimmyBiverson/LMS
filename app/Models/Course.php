<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'price',
        'sale_price',
        'payment_type',
        'duration',
        'status',
        'thumbnail',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
        ];
    }

    public function isFree(): bool
    {
        return $this->payment_type === 'free';
    }

    public function hasSale(): bool
    {
        return !is_null($this->sale_price) && $this->sale_price < $this->price;
    }

    public function displayPrice(): string
    {
        if ($this->isFree()) {
            return 'Free';
        }
        if ($this->hasSale()) {
            return '$' . number_format($this->sale_price, 2);
        }
        return '$' . number_format($this->price, 2);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }
}
