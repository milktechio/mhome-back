<?php

namespace App\Models;

use App\Traits\StoreImage;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variant extends Model
{
    use HasFactory,Uuid,SoftDeletes,StoreImage;

    public $incrementing = false;

    protected $casts = [
        'id' => 'string',
    ];

    protected $storageRoute = 'Marketplace';

    protected $keyType = 'uuid';

    protected $appends = ['image'];

    protected $fillable = [
        'name',
        'description',
        'content',
        'image_url',
        'price',
        'currency',
        'stock',
        'created_by',
        'product_id',
        'active',
        'recurring',
        'stripe_price_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
