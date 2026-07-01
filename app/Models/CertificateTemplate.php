<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'background_image',
        'logo_position',
        'title_font',
        'title_color',
        'body_color',
        'layout',
        'include_qr',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'layout' => 'array',
            'include_qr' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
