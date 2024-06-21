<?php

namespace App\Models;

use App\Traits\StoreImage;
use App\Traits\Uuid;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vote extends Model
{
    use HasFactory, SoftDeletes, Uuid, StoreImage;

    public $incrementing = false;

    protected $keyType = 'uuid';

    protected $hidden = ['image_url'];

    protected $appends = ['image'];

    protected $fillable = ['user_id', 'title', 'options', 'minimum_participations', 'status', 'date_end', 'imagen_url'];

    protected $storageRoute = 'vote';

    protected $withCount = ['participations'];

    protected $casts = [
        'options' => 'array',
    ];

    public function participations()
    {
        return $this->hasOne(Voting::class);
    }

    public function myVote()
    {
        return $this->hasOne(Voting::class)->where('user_id', Auth::user()->id);
    }
}
