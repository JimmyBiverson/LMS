<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Site Content Model
 * 
 * Manages all dynamic site content. This model provides a flexible content
 * management system where content can be stored with different types (text,
 * html, json, image, video) and organized by categories.
 * 
 * @property int $id
 * @property string $key Unique identifier for the content
 * @property string $value Content value (raw or processed)
 * @property string $type Content type: text, html, json, image, video
 * @property string|null $category Content category for grouping
 * @property bool $is_active Whether this content is active/visible
 * @property int $display_order Order for displaying content
 * @property array|null $metadata Additional metadata stored as JSON
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class SiteContent extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'site_content';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'type',
        'category',
        'is_active',
        'display_order',
        'metadata',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'display_order' => 'integer',
            'metadata' => 'array',
        ];
    }

    /**
     * Get the value attribute with type-based casting.
     * 
     * If type is 'json', decode the JSON string to an array.
     * Otherwise, return the raw value.
     *
     * @param string $value
     * @return mixed
     */
    public function getValueAttribute($value): mixed
    {
        if ($this->type === 'json') {
            return json_decode($value, true);
        }
        
        return $value;
    }

    /**
     * Set the value attribute with type-based encoding.
     * 
     * If type is 'json' and value is an array, encode it to JSON string.
     * Otherwise, store the raw value.
     *
     * @param mixed $value
     * @return void
     */
    public function setValueAttribute($value): void
    {
        if ($this->type === 'json' && is_array($value)) {
            $this->attributes['value'] = json_encode($value);
        } else {
            $this->attributes['value'] = $value;
        }
    }

    /**
     * Scope to retrieve only active content.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to retrieve content by category.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $category
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    /**
     * Get content by category, ordered by display_order.
     * 
     * This is a convenience method that combines the byCategory scope
     * with ordering.
     *
     * @param string $category
     * @param bool $activeOnly Whether to return only active content
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getByCategory(string $category, bool $activeOnly = true): \Illuminate\Database\Eloquent\Collection
    {
        $query = static::byCategory($category)->orderBy('display_order');
        
        if ($activeOnly) {
            $query->active();
        }
        
        return $query->get();
    }

    /**
     * Get content by key.
     *
     * @param string $key
     * @param mixed $default Default value if content not found
     * @return mixed
     */
    public static function getByKey(string $key, $default = null): mixed
    {
        $content = static::where('key', $key)->first();
        
        return $content ? $content->value : $default;
    }
}

