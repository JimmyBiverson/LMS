<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IconProvider extends Model
{
    protected $fillable = ['name', 'url', 'status'];
}
