<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transactions extends Model
{
    use HasFactory,Uuid,SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'uuid';

    protected $casts = [
        'id' => 'string',
        'metadata' => 'array',
    ];

    protected $hidden = ['deleted_at', 'updated_at'];

    protected $fillable = [
        'user_id',
        'requested_user_id',
        'quantity',
        'currency',
        'type_id',
        'transaction_hash',
        'transaction_index',
        'metadata',
    ];

    protected function transaction_type()
    {
        return $this->belongsTo(TransactionType::class, 'type_id');
    }
}
