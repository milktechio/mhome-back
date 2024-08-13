<?php

namespace App\Views;

use Illuminate\Database\Eloquent\Model;

class UserView extends Model
{
    protected $keyType = 'uuid';

    protected $table = 'users';

    protected $casts = [
        'id' => 'string',
    ];
}