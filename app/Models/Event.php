<?php

namespace App\Models;

use App\Traits\StoreImage;
use App\Traits\Uuid;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory,SoftDeletes,Uuid,StoreImage;

    public $incrementing = false;

    protected $keyType = 'uuid';

    protected $hidden = ['image_url'];

    protected $appends = ['image'];

    protected $withCount = 'likes';

    protected $fillable = [
        'title', 'body', 'created_by', 'club_id', 'clabe', 'concept', 'is_news', 'is_event', 'price', 'currency',
    ];

    protected $casts = [
        'created_by' => 'string',
    ];

    protected $storageRoute = 'events';

    public function like()
    {
        return $this->hasOne(EventLike::class)->where('user_id', Auth::user()->id);
    }

    public function likes()
    {
        return $this->hasOne(EventLike::class);
    }

    public function comments()
    {
        return $this->hasMany(EventComment::class)->orderBy('created_at', 'desc');
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}
