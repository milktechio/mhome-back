<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids, HasRoles, SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'uuid';

    protected $fillable = [
        'username',
        'email',
        'password',
        'profile_id',
        'email_verified_at',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'login_attemps',
        'email_verified_at',
        'token',
        'token_expire_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function checkPassword($password)
    {
        return Hash::check($password, $this->password);
    }

    public function toJWTarray()
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'roles' => $this->roles,
            // 'profile' => [
            //     'id' => $this->profile->id,
            //     'name' => $this->profile->name,
            //     'lastname' => $this->profile->lastname,
            //     'language' => $this->profile->language,
            // ],
        ];
    }

    public function saveToken($token)
    {
        $jwt = Token::decode($token);

        $tokenData = [
            'expires_at' => date('Y-m-d H:i:s', $jwt['exp']),
            'jti' => $jwt['jti'],
            'revoked' => 0,
        ];

        $this->tokens()->update([
            'revoked' => 1,
            'user_id' => $this->id,
        ]);
        $this->tokens()->create($tokenData);
    }
    public function tokens()
    {
        return $this->hasMany(Token::class);
    }
}
