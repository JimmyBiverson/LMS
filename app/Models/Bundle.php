<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Bundle extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'price', 'sale_price',
        'level', 'thumbnail', 'status', 'user_id',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Bundle $bundle) {
            if (empty($bundle->slug)) {
                $bundle->slug = Str::slug($bundle->title) . '-' . Str::random(5);
            }
        });
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'bundle_course');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function hasSale(): bool
    {
        return !is_null($this->sale_price) && $this->sale_price < $this->price;
    }

    public function displayPrice(): string
    {
        if ($this->hasSale()) {
            return '$' . number_format($this->sale_price, 2);
        }
        return '$' . number_format($this->price, 2);
    }

    public function totalCourses(): int
    {
        return $this->courses()->count();
    }
}
