<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'price', 'duration', 'duration_months', 'features', 'status'];

    protected function casts(): array
    {
        return ['features' => 'array', 'price' => 'decimal:2'];
    }

    public function userSubscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }
}
