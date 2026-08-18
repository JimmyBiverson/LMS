<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteLanguage extends Model
{
    protected $fillable = ['name', 'code', 'is_default', 'status'];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }
}
