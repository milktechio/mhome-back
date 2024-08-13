<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class Stripe extends Model
{
    use HasFactory, SoftDeletes, Uuid;

    protected $fillable = [
        'product_id',
        'user_id',
        'price',
        'stripe_id',
        'balance_transaction',
    ];  
}
