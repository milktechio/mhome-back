<?php

namespace App\Models;

use App\Traits\Uuid;
use App\Views\UserView;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventComment extends Model
{
    use SoftDeletes,Uuid;

    public $incrementing = false;

    protected $keyType = 'uuid';

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = [
        'event_id', 'user_id', 'comment',
    ];

    protected $with = 'user';

    public function user()
    {
        return $this->belongsTo(UserView::class, 'user_id')->select('id', 'username');
    }
}
