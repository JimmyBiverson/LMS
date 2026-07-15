<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'user_id',
        'title',
        'summary',
        'content',
        'attachment_path',
        'external_link',
        'display_order',
        'status',
        'allow_download',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'allow_download' => 'boolean',
            'display_order' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getAttachmentUrlAttribute(): ?string
    {
        if (!$this->attachment_path) {
            return null;
        }

        return 
            str_starts_with($this->attachment_path, 'http://') || str_starts_with($this->attachment_path, 'https://')
                ? $this->attachment_path
                : Storage::disk('public')->url($this->attachment_path);
    }

    public function getAttachmentNameAttribute(): ?string
    {
        if (!$this->attachment_path) {
            return null;
        }

        return basename($this->attachment_path);
    }

    public function getDownloadLabelAttribute(): string
    {
        $title = trim((string) ($this->title ?? ''));

        return $title !== '' ? $title : ($this->attachment_name ?? 'Download file');
    }

    public function getDownloadFilenameAttribute(): string
    {
        return $this->attachment_name ?? 'download';
    }
}
