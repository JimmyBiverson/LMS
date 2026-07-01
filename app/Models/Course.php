<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'instructor_id',
        'category_id',
        'title',
        'slug',
        'description',
        'category',
        'price',
        'sale_price',
        'payment_type',
        'duration',
        'status',
        'thumbnail',
        'outcomes',
        'requirements',
        'level_id',
        'preview_video',
        'thumbnail_updated_at',
        'is_featured',
    ];

    protected static function booted(): void
    {
        static::creating(function (Course $course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title) . '-' . Str::random(5);
            }
        });

        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('homepage.courses');
            \Illuminate\Support\Facades\Cache::forget('homepage.featured_courses');
            \Illuminate\Support\Facades\Cache::forget('homepage.stats');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('homepage.courses');
            \Illuminate\Support\Facades\Cache::forget('homepage.featured_courses');
            \Illuminate\Support\Facades\Cache::forget('homepage.stats');
        });
    }

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'thumbnail_updated_at' => 'datetime',
            'is_featured' => 'boolean',
        ];
    }

    public function isFree(): bool
    {
        return $this->payment_type === 'free';
    }

    public function hasSale(): bool
    {
        return !is_null($this->sale_price) && $this->sale_price < $this->price;
    }

    public function displayPrice(): string
    {
        if ($this->isFree()) {
            return 'Free';
        }
        if ($this->hasSale()) {
            return '$' . number_format($this->sale_price, 2);
        }
        return '$' . number_format($this->price, 2);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedInstructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function categoryRelation(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function averageRating(): float
    {
        return $this->reviews()->where('is_approved', true)->average('rating') ?? 0;
    }

    public function getPreviewVideoUrlAttribute(): ?string
    {
        if (!$this->preview_video) {
            return null;
        }

        return \Storage::url($this->preview_video);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if (!$this->thumbnail || $this->thumbnail === 'N/A') {
            return null;
        }

        if (str_starts_with($this->thumbnail, 'http://') || str_starts_with($this->thumbnail, 'https://')) {
            return $this->thumbnail;
        }

        if (!\Storage::disk('public')->exists($this->thumbnail)) {
            return null;
        }

        return \Storage::url($this->thumbnail);
    }

    public function getEnrollmentCountAttribute(): int
    {
        // Use cached enrollment_count if available, otherwise calculate
        if (isset($this->attributes['enrollment_count'])) {
            return $this->attributes['enrollment_count'];
        }

        return $this->enrollments()->count();
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function prerequisites(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_prerequisite', 'course_id', 'prerequisite_id');
    }

    public function dependentCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_prerequisite', 'prerequisite_id', 'course_id');
    }

    public function courseAnalytics(): HasMany
    {
        return $this->hasMany(CourseAnalytics::class);
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function getResumeLesson(?int $userId = null): ?\App\Models\Lesson
    {
        $userId = $userId ?? auth()->id();
        if (!$userId) {
            return $this->lessons()->orderBy('order')->first();
        }

        $completedIds = \App\Models\LessonCompletion::where('user_id', $userId)
            ->where('course_id', $this->id)
            ->whereNotNull('completed_at')
            ->pluck('lesson_id')
            ->toArray();

        $firstUncompleted = $this->lessons()
            ->orderBy('order')
            ->get()
            ->first(fn($l) => !in_array($l->id, $completedIds));

        if ($firstUncompleted) {
            return $firstUncompleted;
        }

        $lastCompletion = \App\Models\LessonCompletion::where('user_id', $userId)
            ->where('course_id', $this->id)
            ->orderBy('updated_at', 'desc')
            ->first();

        if ($lastCompletion) {
            $lesson = $this->lessons()->find($lastCompletion->lesson_id);
            if ($lesson) {
                return $lesson;
            }
        }

        return $this->lessons()->orderBy('order')->first();
    }
}
