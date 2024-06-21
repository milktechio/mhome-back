<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventLike extends Model
{
    use SoftDeletes,Uuid;

    public $incrementing = false;

    protected $keyType = 'uuid';

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = [
        'event_id', 'user_id',
    ];
}
