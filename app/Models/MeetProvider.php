<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetProvider extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'api_key', 'config', 'status'];

    protected function casts(): array
    {
        return ['config' => 'array'];
    }
}
