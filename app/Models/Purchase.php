<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory,Uuid,SoftDeletes;

    public $incrementing = false;

    protected $casts = [
        'id' => 'string',
    ];

    protected $keyType = 'uuid';

    protected $fillable = [
        'user_id',
        'product_id',
        'company_id',
        'variant_id',
        'seller_id',
        'price',
        'currency',
        'detail',
        'sold',
        'total',
        'latitude',
        'longitude',
        'commission',
        'status',
        'buyer_status',
        'purchased_by',
        'transaction_id',
        'currency_price',
        'created_at',
        'updated_at',
        'charged',
        'payment',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function comments()
    {
        return $this->hasMany(PurchaseComment::class)->orderBy('created_at', 'desc');
    }
}

