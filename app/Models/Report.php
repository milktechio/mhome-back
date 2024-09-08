<?php

namespace App\Models;

use App\Traits\StoreImage;
use App\Traits\Uuid;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory, SoftDeletes, Uuid, StoreImage;

    public $incrementing = false;

    protected $keyType = 'uuid';

    protected $appends = ['image'];

    protected $storageRoute = 'reports';

    protected $fillable = ['description', 'user_id', 'image_url', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
