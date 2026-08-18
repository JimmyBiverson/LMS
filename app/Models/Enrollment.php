<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'course_id',
        'amount_paid',
        'payment_method_id',
        'payment_provider',
        'payment_reference',
        'payment_status',
        'approval_status',
        'approved_by',
        'approved_at',
        'status',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'amount_paid' => 'decimal:2',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isApprovedForAccess(): bool
    {
        if ($this->relationLoaded('course') && $this->course) {
            $paidCourse = $this->course->payment_type === 'paid';
        } else {
            $paidCourse = $this->course?->payment_type === 'paid';
        }

        if (!$paidCourse) {
            return true;
        }

        return in_array($this->approval_status, ['approved', 'auto_approved'], true)
            && in_array($this->payment_status ?? 'approved', ['approved', 'paid'], true);
    }
}
