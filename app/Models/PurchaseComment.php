<?php

namespace App\Models;

use App\Traits\StoreImage;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use App\Views\UserView;

class PurchaseComment extends Model
{
    use SoftDeletes,Uuid;

    public $incrementing = false;

    protected $keyType = 'uuid';

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = [
        'purchase_id', 'user_id', 'comment'
    ];

    protected $with='user';

    public function user()
    {
        return $this->belongsTo(UserView::class, 'user_id')->select('id', 'username');
    }

}
