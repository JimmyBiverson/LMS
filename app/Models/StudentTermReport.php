<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentTermReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'instructor_id',
        'authorized_by',
        'term',
        'academic_year',
        'subject',
        'marks',
        'grade',
        'remarks',
        'status',
        'report_url',
        'visible_to_student',
        'school_fees_paid',
    ];

    protected function casts(): array
    {
        return [
            'visible_to_student' => 'boolean',
            'school_fees_paid' => 'boolean',
            'marks' => 'decimal:2',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function authorizedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'authorized_by');
    }

    public function canStudentSee(): bool
    {
        return (bool) $this->visible_to_student
            && (bool) $this->student?->school_fees_paid
            && (bool) $this->school_fees_paid;
    }

    public function isVisibleToStudent(): bool
    {
        return $this->canStudentSee();
    }
}
