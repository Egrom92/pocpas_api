<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subscriber extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
    }
}
