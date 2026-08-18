<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    protected $fillable = [
        'title', 'course_id', 'class_id', 'exam_date', 'start_time', 'end_time',
        'total_marks', 'passing_marks', 'exam_type', 'description', 'status',
    ];

    protected function casts(): array
    {
        return [
            'exam_date' => 'date',
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'total_marks' => 'integer',
            'passing_marks' => 'decimal:2',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(LmsClass::class, 'class_id');
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }
}
