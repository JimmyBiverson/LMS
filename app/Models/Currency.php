<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = ['name', 'code', 'symbol', 'rate', 'is_default', 'status'];

    protected function casts(): array
    {
        return [
            'rate' => 'decimal:4',
            'is_default' => 'boolean',
        ];
    }
}
