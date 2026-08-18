<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    protected $fillable = [
        'school_name', 'school_email', 'school_phone', 'school_address',
        'currency_symbol', 'currency_code', 'currency_position',
        'timezone', 'language',
        'favicon', 'site_logo',
        'primary_color', 'secondary_color', 'accent_color', 'custom_css',
        'slider_video',
    ];

    protected static function booted(): void
    {
        static::saved(function ($model) {
            \Illuminate\Support\Facades\Cache::forget('school_settings');
        });
    }

    public static function getInstance(): self
    {
        $attributes = \Illuminate\Support\Facades\Cache::rememberForever('school_settings', function () {
            return self::firstOrCreate([], [])->getAttributes();
        });

        $instance = new self();
        $instance->exists = true;
        $instance->setRawAttributes($attributes, true);
        return $instance;
    }

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $setting = self::getInstance();
        return $setting->$key ?? $default;
    }

    public static function setValue(string $key, mixed $value): void
    {
        $setting = self::getInstance();
        $setting->$key = $value;
        $setting->save();
    }
}
