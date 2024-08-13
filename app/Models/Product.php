<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\StoreImage;

class Product extends Model
{
    use HasFactory,Uuid,SoftDeletes,StoreImage;

    public $incrementing = false;

    protected $casts = [
        'id' => 'string',
    ];
    protected $storageRoute = 'products';

    protected $keyType = 'uuid';
    protected $appends = ['image'];

    protected $fillable = [
        'name',
        'content',
        'description',
        'short_description',
        'image_url',
        'sold',
        'state',
        'country',
        'active',
        'commission_id',
        'user_id',
    ];

    public function transactions()
    {
        return $this->hasMany(Transactions::class);
    }

    public function variant()
    {
        return $this->hasMany(Variant::class);
    }
}
