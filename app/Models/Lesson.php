<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_id',
        'title',
        'content',
        'video_url',
        'video_file',
        'document_file',
        'duration',
        'order',
        'is_free_preview',
        'status',
    ];

    /**
     * Returns true if the lesson has at least one media source.
     */
    public function hasMedia(): bool
    {
        return $this->video_url || $this->video_file || $this->document_file;
    }

    public function videoSource(): ?string
    {
        if ($this->video_file) {
            return asset('storage/' . $this->video_file);
        }
        return $this->video_url;
    }

    protected function casts(): array
    {
        return [
            'is_free_preview' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function completions(): HasMany
    {
        return $this->hasMany(LessonCompletion::class);
    }
}
