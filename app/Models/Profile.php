<?php

namespace App\Models;

// use App\Traits\StoreImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Profile extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    public $incrementing = false;

    protected $keyType = 'uuid';

    public $appends = ['image', 'fullName'];

    protected $hidden = ['image_url', 'created_at', 'updated_at', 'deleted_at'];

    protected $storageRoute = 'profile';

    protected $fillable = [
        'name',
        'lastname',
        'email',
        'mobile',
        'image_url',
        'gender',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function getfullNameAttribute()
    {
        return $this->name.' '.$this->lastname;
    }
}
