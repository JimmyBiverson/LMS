<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Noticeboard extends Model
{
    protected $fillable = ['user_id', 'title', 'content', 'status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
