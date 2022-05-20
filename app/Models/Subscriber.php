<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    /**
     * @var false|mixed
     */
    public mixed $authorization_state;
    public mixed $authorization_time;

    protected static function boot()
    {
        parent::boot();
    }
}

