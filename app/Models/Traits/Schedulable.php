<?php

namespace App\Models\Traits;

trait Schedulable
{
    public function isAvailable(): bool
    {
        if ($this->status !== 'published') {
            return false;
        }
        return $this->available_from === null || $this->available_from->isPast();
    }

    public function isUpcoming(): bool
    {
        return $this->status === 'published'
            && $this->available_from !== null
            && $this->available_from->isFuture();
    }

    public function availabilityBadge(): string
    {
        if ($this->status === 'draft') {
            return 'draft';
        }
        if ($this->isUpcoming()) {
            return 'scheduled';
        }
        return 'available';
    }
}
