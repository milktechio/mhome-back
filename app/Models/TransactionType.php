<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionType extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    protected $keyType = 'uuid';

    protected $casts = [
        'id' => 'string',
    ];

    protected $hidden = ['deleted_at', 'updated_at'];

    public static function getId($name)
    {
        $type = TransactionType::whereName($name)->first() ?? false;

        if (! $type) {
            $type = new TransactionType;
            $type->name = $name;
            $type->save();
        }

        return $type->id;
    }
}
