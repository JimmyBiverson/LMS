<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'type',
        'template_name',
        'subject',
        'body',
        'status',
    ];
}
